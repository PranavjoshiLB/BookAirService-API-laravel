<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 3:18 PM
 */

namespace App\Filters\FareMasterPricer;


use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use App\Filters\FilterInterface;

class FlightTypeFilter implements FilterInterface
{

    public $key = "requestedFlightTypes";

    /**
     * FLIGHTTYPE_DIRECT = "D";
     * FLIGHTTYPE_NONSTOP = "N";
     * FLIGHTTYPE_CONNECTING = "C";
     *
     * @param array $param
     * @return array
     */
    public function apply($value)
    {
        return $value;
    }
}