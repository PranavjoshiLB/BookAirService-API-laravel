<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 1:26 PM
 */

namespace App\Filters\FlightAvailability;


use Amadeus\Client\RequestOptions\AirMultiAvailabilityOptions;
use App\Filters\FilterInterface;

class ActionCodeFilter implements FilterInterface
{
    public $key = "actionCode";

    /**
     * @param null $param
     * @return int
     */
    public function apply($param = null)
    {
        if ($param == null) {
            return AirMultiAvailabilityOptions::ACTION_AVAILABILITY;
        }
        return $param;
    }
}