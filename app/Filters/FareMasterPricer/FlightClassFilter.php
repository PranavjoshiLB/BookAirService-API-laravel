<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 3:28 PM
 */

namespace App\Filters\FareMasterPricer;

use App\Filters\FilterInterface;

/**
 * Class FlightClassFilter
 * @package App\Filters
 */
class FlightClassFilter implements FilterInterface
{
    public $key ="cabinClass";

    /**
     * CABIN_ECONOMY = "Y";
     * CABIN_ECONOMY_STANDARD = "M";
     * CABIN_ECONOMY_PREMIUM = "W";
     * CABIN_BUSINESS = "C";
     * CABIN_FIRST_SUPERSONIC = "F";
     * @param $value
     * @return mixed
     */
    public  function apply($value)
    {
       return $value;
    }
}