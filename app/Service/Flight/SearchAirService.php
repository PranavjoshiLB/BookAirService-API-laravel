<?php

namespace App\Service\Flight;
use Amadeus\Client;
use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo;
use Amadeus\Client\RequestOptions\AirMultiAvailabilityOptions;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use App\Filters\SearchFilter;
use Amadeus\Client\RequestOptions\FareInformativeBestPricingWithoutPnrOptions;
use App\Service\Client\AmadeusClient;
use App\Service\Flight\Contract\SearchAirService as Contract;
class SearchAirService extends BaseFlight implements Contract
{

    use SearchFilter;
    /**
     * White listed filters that can be applied to fare master travel board search
     * @var array
     */
    protected $fareTravelBoardFilters = [
        'itinerary',
        'passenger',
        'totalPassenger',
        'totalResult',
        'flightType',
        'flightClass',
        'currency',
        'cabinClass',
        'airlines'
    ];
    /**
     *  White listed filters that can be applied to multi availability
     * @var array
     */
    protected $availabilityFilters = [
        'actionCode',
        'RequestOption'
    ];

    /**
     *  White listed filters for Air_SellfromRecommendation
     * @var array
     */
    protected $pricingFilters = [
        'pricingOption',
        'traveller',
        'segment'
    ];

    /**
     * @var AmadeusClient
     */
    protected $client;

    public function __construct(AmadeusClient $client)
    {
        $this->client = $client;
    }

    /***
     * @param bool $adult
     * @return Client\Result
     */
    public function searchOneWay()
    {
    }

    /**
     * @param int $noOfPassengers
     * @return Client\Result
     */
    public function fareMasterBoardSearch(array $params)
    {
        $path = "FareMasterPricer";
        $applicableFilters = $this->fareTravelBoardFilters;
        $options = $this->applyFilters($params, $applicableFilters, $path)
            ->getRequestOptions();

        //enforce a mandatory cabin option class
        $options['cabinOption'] = FareMasterPricerTbSearch::CABINOPT_MANDATORY;

        return $this->client->fareMasterPricerTravelBoardSearch(new FareMasterPricerTbSearch($options));
    }

    /**
     * @param array $params
     */
    public function fareInformativeBestPricingWithoutPnr(array $params)
    {
        $whitelistedFilters = $this->pricingFilters;
        $path = "InformativePricing";
        $options = $this->applyFilters($params, $whitelistedFilters, $path)->getRequestOptions();
        $opts = new FareInformativeBestPricingWithoutPnrOptions($options);
        return $this->client
            ->fareInformativeBestPricingWithoutPnr($opts);
    }

    /**
     * @return Client\Result
     */
    public function multiAvailability(array $params)
    {
        $path = "FlightAvailability";
        $applicableFilters = $this->availabilityFilters;
        $options = $this->applyFilters($params, $applicableFilters, $path)->getRequestOptions();
        $opt = new AirMultiAvailabilityOptions($options);

        return $this->client
            ->airMultiAvailability($opt);
    }

    /**
     * Retrieve seat map
     * @return Client\Result
     */
    public function seatMap()
    {
        $params = [
            'departureDate' => $this->getDepartureDate(),
            'departure' => $this->getDepartureLocation(),
            'arrival' => $this->getArrivalLocation(),
            'airline' => $this->getAirline(),
            'flightNumber' => $this->getFlightNumber()
        ];
        if (isset($this->bookingClass)) {
            $params['bookingClass'] = $this->getBookingClass();
        }
        return $this->client
            ->airRetrieveSeatMap(
            new AirRetrieveSeatMapOptions([
                'flight' => new FlightInfo($params)
            ])
        );
    }
}