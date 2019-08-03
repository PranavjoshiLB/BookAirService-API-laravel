<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/4/2018
 * Time: 9:45 PM
 */

namespace App\Processor;

use Robbo\Presenter\Presenter;

class AirSellRecomProcessor extends Presenter implements ProcessorInterface
{
    const SOLD = "OK";
    const  UNABLE_TO_SELL = "UNS";
    const WAIT_LISTED = "WL";
    const CANCELLED = "X";
    const NOT_ATTEMPTED = "RQ";


    public function segmentInfo()
    {
        return $this->itineraryDetails['segmentInformation'];
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return isset($this->errorAtMessageLevel) && $this->errorAtMessageLevel != null;
    }

    /**
     * @return mixed
     */
    public function getItinerary()
    {
        $info = [];
        $details = $this->itineraryDetails;

        if (isset($details[0])) {
            foreach ($details as $row) {
                $info[] = $row;
            }
            return $info;
        }

        return array($details);
    }

    /**
     * @return mixed
     */
    public function originInfo()
    {
        return $this->itineraryDetails['originDestination'];
    }

    public function segmentArray($itinerary)
    {
        $data = [];
        $segmentInfo = $itinerary['segmentInformation'];
        if (isset($segmentInfo[0])) {
            foreach ($segmentInfo as $info) {
                $data[] = $this->getSegData($info);
            }
        } else {
            $data[] = $this->getSegData($segmentInfo);
        }
        return $data;
    }

    public function getSegData($info)
    {
        $flightDetails = $info['flightDetails'];
        $ApdSegment = $info['apdSegment'];
        $actionDetails = $info['actionDetails'];
        $data['dateOfDeparture'] = $flightDetails['flightDate']['departureDate'];
        $data['timeOfDeparture'] = $flightDetails['flightDate']['departureTime'];
        $data['timeOfArrival'] = $flightDetails['flightDate']['arrivalTime'];
        $data['departureLocation'] = $flightDetails['boardPointDetails']['trueLocationId'];
        $data['arrivalLocation'] = $flightDetails['offpointDetails']['trueLocationId'];
        $data['flightNumber'] = $flightDetails['flightIdentification']['flightNumber'];
        $data['bookingClass'] = $flightDetails['flightIdentification']['bookingClass'];
        $data['statusCode'] = $actionDetails['statusCode'];
        $data['quantity'] = $actionDetails['quantity'];
        return $data;
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        $output = [];
        $itineraries = $this->getItinerary();
        foreach ($itineraries as $itinerary) {
            $output[] = $this->segmentArray($itinerary);
        }
        return collect($output)->flatten(1)->toArray();
    }
}