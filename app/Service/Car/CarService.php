<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/8/2018
 * Time: 10:56 PM
 */

namespace App\Service\Car;


use Amadeus\Client;
use App\Service\Car\Contracts\CarServiceInterface;
use App\Service\RequestOption\Car\CarRequestOption;

/**
 * Class CarService
 * @package App\Service\Car
 */
class CarService implements CarServiceInterface
{

    protected $client;

    public function __construct(CarClient $client)
    {
        $this->client = $client;
    }

    public function RateInfo()
    {
    }

    /**
     * @param CarRequestOption $option
     * @return Client\Result
     */
    public function carAvailability(CarRequestOption $option)
    {
        return $this->client->carAvailability($option);
    }

    /**
     * @param CarRequestOption $option
     * @return Client\Result
     */
    public function carLocationList(CarRequestOption $option)
    {
        return $this->client->carLocationList($option);
    }
}