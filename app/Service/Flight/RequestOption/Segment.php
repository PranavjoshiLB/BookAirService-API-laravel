<?php

namespace App\Service\Flight\RequestOption;

use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Segment as FlightSegment;
use Carbon\Carbon;

class Segment extends FlightSegment
{

    public $arrivalDate;

    public function __construct(array $params = [])
    {
        parent::__construct($params);
        $this->setDepartureDate($params);
        $this->setArrivalDate($params);
        $this->setArrivalLocation($params);
        $this->setDepartureLocation($params);
        $this->setCompany($params);
        $this->setTotalPassenger($params);
        $this->setFlightTypeDetails($params); //support for slice and dice operation
    }

    /**
     * @param $params
     */
    public function setDepartureDate($params)
    {
        if (isset($params['dateOfDeparture']) && isset($params['timeOfDeparture'])) {
            $dateOfDeparture = $params['dateOfDeparture'] . " " . $params['timeOfDeparture'];
            $this->departureDate = Carbon::parse($dateOfDeparture);
        }
    }

    /**
     * @param $params
     */
    public function setArrivalDate($params)
    {
        if (isset($params['dateOfArrival']) && isset($params['timeOfArrival'])) {
            $this->arrivalDate = Carbon::parse($params['dateOfArrival'] . " " . $params['timeOfArrival']);
        }
    }

    /**
     * @param $params
     */
    public function setDepartureLocation($params)
    {
        $this->from = $params['departureLocation'];
    }

    /**
     * @param $params
     */
    public function setArrivalLocation($params)
    {
        $this->to = $params['arrivalLocation'];
    }

    /**
     * @param $params
     */
    public function setCompany($params)
    {
        $this->companyCode = $params['marketingCompany'];
    }

    /**
     * @param $params
     */
    public function setTotalPassenger($params)
    {
        $this->nrOfPassengers = $params['totalPassenger'];
    }

    public function setFlightTypeDetails($params)
    {
        if (isset($params['availabilityCtx']) && $params['availabilityCtx'] != false) {
            $this->flightTypeDetails = $params['availabilityCtx'];
        }
    }
}