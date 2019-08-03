<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/12/2018
 * Time: 12:53 PM
 */

namespace App\Service\Flight;

use Amadeus\Client;
use App\Exceptions\AirSellRecommendationException;
use Amadeus\Client\RequestOptions\AirSellFromRecommendationOptions;
use App\Exceptions\CreateTstException;
use App\Exceptions\FareDiscrepancyException;
use App\Exceptions\PnrCreateException;
use App\Exceptions\PricePnrException;
use App\Processor\PnrAddElementProcessor;
use App\Service\Client\AmadeusClient;
use App\Service\Flight\RequestOption\Itinerary;
use Nathanmac\Utilities\Parser\Parser;
use App\Service\Flight\Contract\BookAirService as Contract;

class BookAirService implements Contract
{
    protected $pnrElementFilters = [
        'contact',
        'address',
    ];

    /**
     * @var Client $client
     */
    protected $client;

    /**
     * @var Itinerary [] $itinerary
     */
    protected $itinerary;

    /**
     * @var Client\RequestOptions\Pnr\Element [] $elements
     */
    protected $elements;

    /**
     * @var Client\RequestOptions\Pnr\Traveller [] $passengers
     */
    protected $passengers;

    protected $fareAmount;

    /**
     * BookingService constructor.
     *
     * @param AmadeusClient $client
     */
    public function __construct(AmadeusClient $client)
    {
        if (! $client->isStateful()) {
            $client->setStateful(true);
        }
        $this->client = $client;
    }

    /**
     * @param Itinerary[] $itinerary
     */
    public function setItinerary(array $itinerary)
    {
        $this->itinerary = $itinerary;

        return $this;
    }

    /**
     * @param Client\RequestOptions\Pnr\Element [] $elements
     */
    public function addElement($element)
    {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function setFareAmount($amount)
    {
        $amount = (float) $amount;
        $this->fareAmount = $amount;

        return $this;
    }

    /**
     * @param Client\RequestOptions\Fare\InformativePricing\Passenger [] $passengers
     */
    public function setTravellers($passengers)
    {
        $this->passengers = $passengers;

        return $this;
    }

    /**
     * @return Client\Result
     * @throws \Exception
     */
    public function airSellFromRecommendation()
    {
        if (! isset($this->itinerary)) {
            throw new \Exception('Itinerary must be set for air_SellFromRecommendation');
        }
        $opts = new AirSellFromRecommendationOptions([
            'itinerary' => $this->itinerary,
        ]);

        return $this->client->airSellFromRecommendation($opts);
    }

    /**
     * @return Client\Result
     */
    public function addPNR()
    {
        $opt = new Client\RequestOptions\PnrCreatePnrOptions([
            //0 Do not yet save the PNR and keep in context.
            'actionCode' => Client\RequestOptions\PnrCreatePnrOptions::ACTION_NO_PROCESSING,
            'travellers' => $this->passengers,
            'elements' => [
                new Client\RequestOptions\Pnr\Element\Ticketing([
                    'ticketMode' => Client\RequestOptions\Pnr\Element\Ticketing::TICKETMODE_OK,
                ]),
            ],
        ]);

        return $this->client->pnrCreatePnr($opt);
    }

    public function pricePnr()
    {
        $opts = new Client\RequestOptions\FarePricePnrWithLowerFaresOptions([
            'overrideOptions' => [
                Client\RequestOptions\FarePricePnrWithLowerFaresOptions::OVERRIDE_FARETYPE_PUB,
                Client\RequestOptions\FarePricePnrWithLowerFaresOptions::OVERRIDE_FARETYPE_UNI,
            ],
        ]);

        return $this->client->farePricePnrWithLowerFares($opts);
    }

    /**
     * @return Client\Result
     */
    public function createTSTFromPricing($numOfTickets)
    {
        $tstNumber = [];
        for ($i = 1; $i <= $numOfTickets; $i++) {
            $tstNumber[] = new Client\RequestOptions\Ticket\Pricing([
                'tstNumber' => $i,
            ]);
        }

        return $this->client->ticketCreateTSTFromPricing(new Client\RequestOptions\TicketCreateTstFromPricingOptions([
            'pricings' => $tstNumber,
        ]));
    }

    /**
     * This checks if a given air segment can be booked.
     */
    public function isBookable()
    {
        $response = $this->airSellFromRecommendation();
        if ($response->status === Client\Result::STATUS_OK) {
            return true;
        }

        return false;
    }

    /**
     * @return Client\Result
     */
    public function saveTransaction()
    {
        return $this->client->pnrAddMultiElements(new Client\RequestOptions\PnrAddMultiElementsOptions([
            //ET: END AND RETRIEVE
            'actionCode' => Client\RequestOptions\PnrAddMultiElementsOptions::ACTION_END_TRANSACT_RETRIEVE,
            'elements' => $this->elements,
        ]));
    }

    public function queuePnr($controlNumber)
    {

        return $this->client->queuePlacePnr(new Client\RequestOptions\QueuePlacePnrOptions([
            'targetQueue' => new Client\RequestOptions\Queue([
                'queue' => 50,
                'category' => 0,
                //'officeId'=>''
            ]),
            'recordLocator' => $controlNumber,
        ]));
    }

    /**
     *  Convert XML to an array
     *
     * @param $xml
     * @return array
     */
    private function xmlToArray($xml)
    {
        return (new Parser())->xml($xml);
    }

    /**
     *  This function compares the fare returned from Fare_PricePNRWithLowerFares response with
     *  the one returned by fare_informativePricingWithoutPNR
     *  Returns false if it does not match
     *
     * @param float $initAmount
     * @param $fareResponse
     * @return mixed
     */
    private function fareHasDeviation(float $initAmount, $fareResponse)
    {
        $arrayResponse = $this->xmlToArray($fareResponse);
        if (! isset($arrayResponse['fareList']['fareDataInformation']['fareDataSupInformation'])) {
            return true;
        }
        $fare = $arrayResponse['fareList']['fareDataInformation']['fareDataSupInformation'];
        $collectionFare = collect($fare);
        $f = $collectionFare->firstWhere('fareDataQualifier', 712);
        $curAmount = (float) $f['fareAmount'];

        return $initAmount !== $curAmount;
    }

    /**
     * Close session
     */
    private function closeSession()
    {
        if ($this->client->isStateful()) {
            $this->client->securitySignOut();
        }
    }

    /**
     * @return PnrAddElementProcessor
     * @throws AirSellRecommendationException
     * @throws PnrCreateException
     * @throws PricePnrException
     */
    public function makeBooking()
    {
        $result = $this->airSellFromRecommendation();
        if ($result->status === Client\Result::STATUS_OK) {
            //Add passenger records
            $response = $this->addPNR();
            if ($response->status === Client\Result::STATUS_OK) {
                // Price PNR
                $pricingResponse = $this->pricePnr();
                if ($pricingResponse->status === Client\Result::STATUS_OK) {

                    // if ($this->fareHasDeviation($this->fareAmount, $pricingResponse->responseXml)) {
                    //   throw new FareDiscrepancyException($pricingResponse);
                    //}
                    $nOfTickets = count($pricingResponse->response->fareList);
                    $tstTicket = $this->createTSTFromPricing($nOfTickets);
                    if ($tstTicket->status === Client\Result::STATUS_OK) {

                        $response = $this->saveTransaction();
                        $result = $this->xmlToArray($response->responseXml);

                        $pnr = new PnrAddElementProcessor($result);
                        $this->queuePnr($pnr->controlNumber());
                        $this->closeSession();

                        return $prn;
                    } else {
                        $this->closeSession();
                        throw new CreateTstException($tstTicket);
                    }
                } else {
                    $this->closeSession();
                    throw new PricePnrException($result);
                }
            } else {
                $this->closeSession();
                throw new PnrCreateException($result);
            }
        } else {
            $this->closeSession();
            throw new AirSellRecommendationException($result);
        }
    }
}