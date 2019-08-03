<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/6/2018
 * Time: 12:01 AM
 */
namespace App\Service\Flight\Contract;

use Amadeus\Client;

interface SearchAirService
{

    /**
     * @param array $array
     * @return mixed
     */
    public function multiAvailability(array $array);

    /**
     * Retrieve seat map
     * @return Client\Result
     */
    public function seatMap();

    /**
     * @param array $params
     * @return mixed
     */
    public function fareMasterBoardSearch(array $params);

    /**
     * @param array $param
     * @return mixed
     */
    public function fareInformativeBestPricingWithoutPnr(array $param);
}