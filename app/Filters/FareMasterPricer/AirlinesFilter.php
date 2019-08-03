<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 6/1/2018
 * Time: 4:24 PM
 */

namespace App\Filters\FareMasterPricer;


use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use App\Filters\FilterInterface;

class AirlinesFilter implements FilterInterface
{

    public $key = "airlineOptions";

    public function apply($param)
    {
        return array(FareMasterPricerTbSearch::AIRLINEOPT_PREFERRED => $param);

    }
}