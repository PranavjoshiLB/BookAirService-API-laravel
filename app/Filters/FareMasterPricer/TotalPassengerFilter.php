<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 10:48 AM
 */

namespace App\Filters\FareMasterPricer;


use App\Filters\FilterInterface;

class TotalPassengerFilter implements FilterInterface
{
    public $key = "nrOfRequestedPassengers";

    public function apply($param = 1)
    {
        return $param;
    }
}