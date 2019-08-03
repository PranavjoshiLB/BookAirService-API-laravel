<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/15/2018
 * Time: 9:19 AM
 */

namespace App\Processor;

use App\Model\Flight\Airline;
use App\Model\Flight\Airport;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use MongoDB\Client;
use Robbo\Presenter\Presenter;

class FareMasterPricerProcessor extends Presenter implements ProcessorInterface
{
    protected $journey = [];

    protected $flightData = [];
    protected $airlines=[];

    protected $sliceAndDiceIndicator = [];

    /**
     * WARNING: This code is not the cleanest code in the world, you
     * should try your best to maintain it and make it cleaner. This was written in
     * haste.
     *
     */
    public function process()
    {
        $recommendations = $this->recommendation();

        foreach ($recommendations as $recommendation) {

            $refCollection = $this->getFlightSegmentRef($recommendation);

            $references = $refCollection->map(function ($row, $index) {

                $rowCollection = collect($row);
                $filteredValues = $rowCollection->whereIn('refQualifier', ['S'])->values();

                return $filteredValues;
            });

            $rec = $this->filterRecommendation($recommendation); //Filter useful data from recommendation data;

            $flights = [];

            if (! empty($references)) {

                foreach ($references as $reference) {

                    $reference->each(function ($ref, $key) use (
                        $recommendation,
                        $rec
                    ) { // This loop can only be a maximum of 2 for each reference. If it's two.
                        $refNumber = $ref['refNumber'];

                        $availabilityCtxArray = [];

                        if ($this->isSlicedAndDiced($recommendation)) {
                            $specificRec = $this->getSpecificRecDetail($recommendation);
                            $availabilityCtxArray = $this->availabilityContext($specificRec['specificProductDetails']);
                            // $this->addSliceAndDiceIndicator($flightSegmentReference, $availabilityCtxArray);
                        }
                        $groupOfFlights = isset($this->flightIndex[$key]) ? $this->flightIndex[$key]['groupOfFlights'] : $this->flightIndex['groupOfFlights'];
                        $flights = $this->getMatchingFlightGroup($groupOfFlights, $refNumber, $availabilityCtxArray);
                        $nOfFlights = count($flights);

                        if ($nOfFlights > 0) { // Flights  are available for the specified journey
                            $rec['flights'] = $flights; //add matching flights to recommendation array
                            $this->flightData[$key][] = $rec;
                        }
                    });
                }
            }
        }
    }

    /**
     * @param $recommendation
     * @return bool
     */

    private function isSlicedAndDiced($recommendation)
    {
        return isset($recommendation['specificRecDetails']);
    }

    /**
     * @param $recommendation
     * @return mixed
     */
    private function getSpecificRecDetail($recommendation)
    {
        if (isset($recommendation['specificRecDetails'][0])) {
            return $recommendation['specificRecDetails'][0];
        } else {
            return $recommendation['specificRecDetails'];
        }
    }

    /**
     *  This is very hacky! This 1st element in the array is always outgoing and the second incoming
     *
     * @param array $references
     */
    private function addSliceAndDiceIndicator(array $references, $availabilityCtxArray)
    {
        if (isset($references[0])) {
            $outgoingRef = $references[0];
            $incomingRef = $references[1];
            $this->sliceAndDiceIndicator[1][$outgoingRef['refNumber']] = $availabilityCtxArray;
            $this->sliceAndDiceIndicator[2][$incomingRef['refNumber']] = $availabilityCtxArray;
        } else {
            $this->sliceAndDiceIndicator['outgoing'][$references['refNumber']] = $availabilityCtxArray;
        }
    }

    /**
     *  This will filter out flights in the flight group matching the recommendation reference key
     *
     * @param $groupOfFlights
     * @param $refNumber
     * @param $availabilityCtxArray
     * @return array
     */
    public function getMatchingFlightGroup($groupOfFlights, $refNumber, $availabilityCtxArray = [])
    {

        $data = [];

        $groupOfFlights = isset($groupOfFlights[0]['propFlightGrDetail']) ? $groupOfFlights : [$groupOfFlights];
        $index = $refNumber - 1;
        $flightProposals = $groupOfFlights[$index]['propFlightGrDetail']['flightProposal'];
        $flightDetails = $groupOfFlights[$index]['flightDetails'];;

        $flightRef = $flightProposals[0]['ref']; //Flight reference is always at index 0;

        if ($flightRef == $refNumber) { // add flight to group if recommendation's reference matches flight reference
            // $key represents flight segment here
            $data[] = $this->processFlightDetails($flightDetails, $availabilityCtxArray);
        }

        return collect($data)->collapse()->toArray();
    }

    /**
     * @param $flightDetails
     * @param $availabilityCtxArray
     * @return array
     */
    public function processFlightDetails($flightDetails, $availabilityCtxArray)
    {
        $journey = [];
        $availabilityCnt = false;
        if (isset($flightDetails[0]['flightInformation'])) { //there are more than one flight in this group
            foreach ($flightDetails as $index => $flight) {

                $availabilityCnt = ! empty($availabilityCtxArray) && isset($availabilityCtxArray[$index]) ? $availabilityCtxArray[$index] : false;
                $data = $this->filterFlightData($flight);
                $data['availabilityCtx'] = $availabilityCnt;
                $journey[] = $data;
            }
        } else {
            $availabilityCnt = ! empty($availabilityContextRefNumber) && isset($availabilityContextRefNumber[0]) ? $availabilityContextRefNumber[0] : false;
            $data = $this->filterFlightData($flightDetails);
            $data['availabilityCtx'] = $availabilityCnt;
            $journey[] = $data;
        }

        return $journey;
    }

    /**
     *  Extract useful information from flight data
     *
     * @param $flight
     * @return array
     */
    public function filterFlightData($flight)
    {
        $data = []; //data for this trip
        $flightInfo = isset($flight['flightInformation']) ? $flight['flightInformation'] : false;
        $locations = $flightInfo['location'];
        $timeOfArrival = Carbon::createFromFormat('Hi', $flightInfo['productDateTime']['timeOfArrival']);
        $timeOfDeparture = Carbon::createFromFormat('Hi', $flightInfo['productDateTime']['timeOfDeparture']);

        $duration = $timeOfArrival->diff($timeOfDeparture);

        $data['currency'] = $this->conversionRate();
        $data['dateOfDeparture'] = formatDate($flightInfo['productDateTime']['dateOfDeparture']);
        $data['timeOfDeparture'] = formatTime($flightInfo['productDateTime']['timeOfDeparture']);
        $data['dateOfArrival'] = formatDate($flightInfo['productDateTime']['dateOfArrival']);
        $data['timeOfArrival'] = formatTime($flightInfo['productDateTime']['timeOfArrival']);
        $data['flightDuration'] = $duration->format('%h.%i');

        $data['departureLocation'] = isset($locations[0]['locationId']) ? $locations[0]['locationId'] : false;
        $data['departureAirport'] = $this->getAirport($locations[0]['locationId']);

        $data['departureTerminal'] = isset($locations[0]['terminal']) ? $locations[0]['terminal'] : false;
        $data['arrivalLocation'] = isset($locations[1]['locationId']) ? $locations[1]['locationId'] : false;
        $data['arrivalAirport'] = $this->getAirport($locations[1]['locationId']);
        $data['arrivalTerminal'] = isset($locations[1]['terminal']) ? $locations[1]['terminal'] : false;

        $operatingCompany = isset($flightInfo['companyId']['operatingCarrier']) ? $flightInfo['companyId']['operatingCarrier'] : null;
        $marketingCompany = isset($flightInfo['companyId']['marketingCarrier']) ? $flightInfo['companyId']['marketingCarrier'] : null;
        $data['marketingCompany'] = $this->getAirline($marketingCompany);

        $airline =$this->getAirline($operatingCompany);
        $this->addAirline($airline);

        $data['operatingCompany'] =$airline;

        $data['flightNumber'] = $flightInfo['flightOrtrainNumber'];
        $data['electronicTicketing'] = $flightInfo['addProductDetail']['electronicTicketing'];

        return $data;
    }

    /**
     * @return array
     */
    public function flightIndex()
    {
        $flights = $this->flightIndex;

        $flightsArray = [];

        if (isset($flights[0]['requestedSegmentRef'])) {
            foreach ($flights as $key => $flight) {
                $key = $flight['requestedSegmentRef']['segRef'];
                $flightsArray[$key] = $flight['groupOfFlights'];
            }
        } else {
            $flightsArray[] = $flights['groupOfFlights'];
        }

        return $flightsArray;
    }

    private function isRoundTrip()
    {
        if (isset($this->flightIndex[0]['requestedSegmentRef'])) {
            return true;
        }

        return false;
    }

    /** Returns current currency conversion rate
     *
     * @return \Illuminate\Support\Collection
     */
    public function conversionRate()
    {
        return $this->conversionRate['conversionRateDetail']['currency'];
    }

    /** Returns an array of recommendations from fare_MasterPricerBoard Response
     *
     * @return mixed
     */
    public function recommendation()
    {
        $recomArray = [];
        $recommendations = $this->recommendation;
        if (isset($recommendations[0])) {
            return $recommendations;
        }
        $recomArray[] = $recommendations;

        return $recomArray;
    }

    /**
     *  Returns a collection of Referencing details for a particular recommendation
     *
     * @param array $segmentRefs
     * @return \Illuminate\Support\Collection
     */
    protected function getFlightSegmentRef(array $recommendation)
    {
        $segmentRefs = $recommendation['segmentFlightRef'];

        $referenceCollection = collect();

        if (isset($segmentRefs[0]['referencingDetail'])) { //

            foreach ($segmentRefs as $key => $value) {
                $referenceCollection->push($value['referencingDetail']);
            }

            return $referenceCollection;
        }

        return $referenceCollection->push($segmentRefs['referencingDetail']);
    }

    /** Returns an array of fare information for a given itinerary
     *
     * @param $fareDetails
     * @return mixed
     */
    public function filterFareDetails($fareDetails)
    {
        if (isset($fareDetails[0]['groupOfFares'])) {
            $groupOfFares = $fareDetails[0]['groupOfFares'];
        } else {
            $groupOfFares = $fareDetails['groupOfFares'];
        }

        return isset($groupOfFares[0]['productInformation']) ? $groupOfFares[0]['productInformation'] : $groupOfFares['productInformation'];
    }

    /**
     * @param $recommendation
     */
    protected function filterRecommendation($recommendation)
    {
        $ticketDescription = $this->getTicketDesc($recommendation);
        $paxFareProductArray = isset($recommendation['paxFareProduct'][0]) ? $recommendation['paxFareProduct'] : [$recommendation['paxFareProduct']];

        $paxFareProductCollection = collect($paxFareProductArray);

        $fares = $paxFareProductCollection->map(function ($row) {
            $data['fareAmount'] = (float) $row['paxFareDetail']['totalFareAmount'];
            $data['taxAmount'] = (float) $row['paxFareDetail']['totalTaxAmount'];
            $data['amount'] = $data['fareAmount'] - $data['taxAmount'];
            $data['passengerType'] = $row['paxReference']['ptc'];

            $totalPassenger = 1; //Default number of passengers to even make query
            if (isset($row['paxReference']['traveller'][0]['ref'])) {
                $filtered = (new Collection($row['paxReference']['traveller']))->filter(function ($row, $key) {
                    return isset($row['ref']);
                });
                $totalPassenger = $filtered->count();
            }

            $data['totalPassenger'] = $totalPassenger;

            $data['totalAmount'] = $data['fareAmount'] * $data['totalPassenger'];

            $fareInfo = $this->filterFareDetails($row['fareDetails']);

            $data['fareBasis'] = $fareInfo['fareProductDetail']['fareType'];
            $data['cabin'] = $fareInfo['cabinProduct']['cabin'];
            $data['rbd'] = $fareInfo['cabinProduct']['rbd'];

            return $data;
        });

        $totalAmount = $fares->sum('totalAmount');
        $taxAmount = $fares->sum('taxAmount');

        $fareDetails = $fares->toArray();

        return [
            'totalAggAmount' => $totalAmount,
            'totalAggTax' => $taxAmount,
            'details' => $fareDetails,
        ];
    }

    /**
     *  Return a string of ticket description for a giving fare
     *
     * @param $recommendation
     */

    public function getAirport($iatacode = null)
    {
        $airport = [];

        if (! is_null($iatacode)) {
            $airport = Airport::where('iata', $iatacode)
                ->select('name', 'iata', 'city', 'state')
                ->first();
        }

        return $airport;
    }

    /**
     *
     * @param null $iatacode
     * @return array
     */
    private function getAirline($iatacode = null)
    {
        $airline = [];

        if (strlen($iatacode) == 2) {
            return Airline::where('designator', $iatacode)
                ->select('designator', 'name', 'iata', 'country','logo','logo_small')
                ->first();
        }

        return $airline;
    }

    /**
     * @param $airline
     */
    private function addAirline($airline){

        if(!in_array($airline,$this->airlines)){
            $this->airlines[] =$airline;
        }
    }

    public function getTicketDesc($recommendation)
    {
        if (isset($recommendation['paxFareProduct']['fare'])) {
            $fare = $recommendation['paxFareProduct']['fare'];

            return collect($fare)->map(function ($value) {
                $description = isset($value['pricingMessage']['description']) ? $value['pricingMessage']['description'] : null;

                return is_array($description) ? implode($description, ',') : $description;
            })->all();
        }
    }

    /** This returns array of flight availability context (LA, OD, S1, S2)
     *
     * @param $specificRecDetails
     * @return array
     */
    public function availabilityContext($specificRecDetails)
    {
        $recDetails = isset($specificRecDetails[0]) ? $specificRecDetails[0] : $specificRecDetails;

        $data = [];
        // $cnxDetailsArray = isset($recDetails['fareContextDetails'][0]) ? $recDetails['fareContextDetails'] : [$recDetails['fareContextDetails']];
        $fareContextDetails = isset($recDetails['fareContextDetails'][0]) ? $recDetails['fareContextDetails'][0] : $recDetails['fareContextDetails'];
        // foreach ($cnxDetailsArray as $fareContextDetails) {

        $segRef = $fareContextDetails['requestedSegmentInfo']['segRef'];

        $fareCnx = $fareContextDetails['cnxContextDetails'];

        $availabilityCnxKeys = $this->processContextKey($fareCnx);

        return $availabilityCnxKeys;
        //$data[$segRef] = $availabilityCnxKeys;
        //}

        //return $data;
    }

    /**
     * @param array $fareCnx
     * @return array
     */
    private function processContextKey(array $fareCnx)
    {
        $fareCnxCollection = collect($fareCnx);

        return $fareCnxCollection->map(function ($cnx) {
            return $cnx['fareCnxInfo']['contextDetails']['availabilityCnxType'];
        })->values()->toArray();
    }

    /**
     *  This returns json output of the filtered data
     *
     * @return mixed
     */
    public function getOutput()
    {
        $this->process();

        $data = [];

        $flightData = $this->flightData;

        foreach ($flightData as $key => $value) {

            $noOfFlights = count($value);

            $tripType = $key == 0 ? "outgoing" : "incoming";

            $data[$tripType] = [
                'results' => $value,
                'noOfFlights' => $noOfFlights,
                'airlines' =>$this->airlines
            ];
        }

        return $data;
    }
}