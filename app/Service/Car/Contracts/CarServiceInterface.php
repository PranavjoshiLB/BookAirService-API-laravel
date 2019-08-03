<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/12/2018
 * Time: 12:19 PM
 */
namespace App\Service\Car\Contracts;

use Amadeus\Client;
use App\Service\RequestOption\Car\CarRequestOption;


/**
 * Class CarService
 * @package App\Service\Car
 */
interface CarServiceInterface
{
    public function RateInfo();

    /**
     * @param CarRequestOption $option
     * @return Client\Result
     */
    public function carAvailability(CarRequestOption $option);

    public function carLocationList(CarRequestOption $option);
}