<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/15/2018
 * Time: 7:00 AM
 */

namespace App\Filters\FareMasterPricer;


use App\Filters\FilterInterface;

class CurrencyFilter implements FilterInterface
{
    public $key = "currencyOverride";

    /**
     *  Default to USD if currency is not set
     * @param null $param
     * @return null|string
     *
     */
    public function apply($param = null)
    {
       return is_null($param)?'USD':$param;
    }
}