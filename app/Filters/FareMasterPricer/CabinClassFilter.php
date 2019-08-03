<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/17/2018
 * Time: 9:19 AM
 */

namespace App\Filters\FareMasterPricer;

use App\Filters\FilterInterface;

class CabinClassFilter implements FilterInterface
{
    public $key = "cabinClass";

    public function apply($value)
    {
        return $value;

    }
}