<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/15/2018
 * Time: 11:04 PM
 */
namespace App\Service\Flight\Contract;

use Amadeus\Client;
use Amadeus\Client\RequestOptions\Pnr\Element\Address;
use App\Exceptions\AirSellRecommendationException;
use App\Exceptions\PnrCreateException;
use App\Exceptions\PricePnrException;
use App\Service\Flight\RequestOption\Itinerary;

interface BookAirService
{
    /**
     * @param Itinerary[] $itinerary
     * @return $this
     */
    public function setItinerary(array $itinerary);

    /**
     * @param $elements
     * @return $this
     */
    public function addElement($elements);

    /**
     * @param $amount
     * @return $this
     */
    public function setFareAmount($amount);

    /**
     * @param Client\RequestOptions\Fare\InformativePricing\Passenger [] $passengers
     * @return $this
     */
    public function setTravellers($passengers);

    /**
     * @return Client\Result
     * @throws \Exception
     */
    public function airSellFromRecommendation();

    /**
     * @return Client\Result
     */
    public function addPNR();

    public function pricePnr();

    public function createTSTFromPricing($numOfTickets);

    /**
     * This checks if a given air segment can be booked.
     */
    public function isBookable();

    /**
     * @return Client\Result
     * @throws AirSellRecommendationException
     * @throws PnrCreateException
     * @throws PricePnrException
     */
    public function makeBooking();
}