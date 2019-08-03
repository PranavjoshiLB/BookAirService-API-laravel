<?php

namespace App\Http\Controllers;

use Amadeus\Client;
use App\Exceptions\AmadeusServiceException;
use App\Filters\SearchFilter;
use App\Processor\FareMasterPricerProcessor;
use App\Processor\PriceAndAvailabilityProcessor;
use App\Service\Flight\Contract\SearchAirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Nathanmac\Utilities\Parser\Parser;

class SearchAirController extends ApiController
{
    use SearchFilter;

    protected $service;

    protected $passer;

    /**
     * FlightController constructor.
     *
     * @param SearchAirService $service
     */
    public function __construct(SearchAirService $service)
    {
        $this->service = $service;
        $this->passer = new Parser();
    }

    /**
     * Search trips
     *
     * @param Request $request
     */
    public function searchMultiAvailability(Request $request)
    {
        $this->validate($request, [
            'departureLocation' => 'required|min:3',
            'arrivalLocation' => 'required|min:3',
            'departureDate' => 'required|date|date_format:Y-m-d|after:yesterday',
        ]);

        $params['actionCode'] = null;
        $params['requestOption'] = $request->all();
        $cacheKey = serialize($params);

        if (Cache::has($cacheKey)) { //search term exists in cache
            return ok(Cache::get($cacheKey));
        }

        try {
            $result = $this->service->multiAvailability($params);
            if ($result->status === Client\Result::STATUS_OK) {
                $data = $this->passer->xml($result->responseXml); //parse xml into an array.
                $processedData = new FareMasterPricerProcessor($data);

                return ok(Cache::remember($cacheKey, 60, function () use ($processedData) {
                    return $processedData->getOutput();
                }));
            }

            return fail($result);
        } catch (\Exception $e) {
            fail('Caught exception: '.$e->getMessage()."\n".$e->getFile()."\n".$e->getLine()."\n");
        }

        return fail("Unable to get flight multi availability");
    }

    /**
     *
     * @param Request $request
     */
    public function searchFareMasterBoard(Request $request)
    {
        $this->validate($request, [
            'itinerary' => 'array',
            'itinerary.*.departureLocation' => 'required|min:3',
            'itinerary.*.arrivalLocation' => 'required|min:3',
            'itinerary.*.departureDate' => 'required|date|date_format:Y-m-d H:i|after:yesterday',
            'itinerary.*.timeWindow' => 'numeric',
            //'itinerary.*.numOfStop' => 'numeric',
            'passenger' => 'array',
            'passenger.*.type' => 'required|in:ADT,CH,INF',
            'passenger.*.count' => 'numeric|required',
            'totalPassenger' => 'required|numeric',
            'numOfStop' => 'numeric', //[0,1,2]
            'cabinClass' => 'array', //economy, standard, premium, business and supersonic
            //'airlines.*'=>'array'

        ]);

        $request->request->add([
            'totalResult' => 200,
            'flightType' => ['N','D','C'] //Connecting, Direct,
        ]);

        if ($request->has('numOfStop')) {
            $numOfStop = $request->numOfStop;
            $itinerary = $request->itinerary;
            $modifiedItinerary = [];
            foreach ($itinerary as $trip) {
                $trip['numOfStop'] = $numOfStop;
                $modifiedItinerary[] = $trip;
            }
            $request->merge(['itinerary' => $modifiedItinerary]);
        }

        $response = $this->service->fareMasterBoardSearch($request->all());

        if ($response->status === Client\Result::STATUS_OK) {
            $parsedResponse = $this->passer->xml($response->responseXml);
            $processedData = new FareMasterPricerProcessor($parsedResponse);

            return ok($processedData->getOutput());
        }

        throw new AmadeusServiceException($response);
    }

    /**
     * @param Request $request
     */
    public function fareInformativeBestPricing(Request $request)
    {
        $this->validate($request, [
            'itinerary.*.departureLocation' => 'required|min:3',
            'itinerary.*.arrivalLocation' => 'required|min:3',
            'itinerary.*.dateOfDeparture' => 'required|date|date_format:Y-m-d|after:yesterday',
            'itinerary.*.dateOfArrival' => 'required|date|date_format:Y-m-d|after:yesterday',
            'itinerary.*.timeOfDeparture' => 'required|date_format:H:i',
            'itinerary.*.timeOfArrival' => 'required|date_format:H:i',
            'itinerary.*.marketingCompany' => 'required',
            'itinerary.*.operatingCompany' => 'required',
            'itinerary.*.flightNumber' => 'required',
            'itinerary.*.groupNumber' => 'required|numeric',
            'itinerary.*.bookingClass' => 'required',
            'itinerary' => 'array|required',
            'passengers' => 'array|required',
            'passengers.*.type' => 'required|in:ADT,CH,INF',
            'passengers.*.count' => 'numeric|required',
        ]);
        $params = [
            'traveller' => $request->passengers,
            'segment' => $request->itinerary,
            'pricingOption' => null, // use default  to price itinerary
        ];
        try {
            $response = $this->service->fareInformativeBestPricingWithoutPnr($params);
            if ($response->status === Client\Result::STATUS_OK) {
                $result = $this->passer->xml($response->responseXml);
                $processor = new PriceAndAvailabilityProcessor($result);

                return ok($processor->getOutput());
            }
            throw new AmadeusServiceException($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
