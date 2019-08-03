<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/7/2018
 * Time: 9:55 AM
 */

namespace App\Filters\FareMasterPricer;


use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use App\Filters\FilterInterface;

class PricingOptionFilter implements FilterInterface
{
    public $key = "flightOptions";

    public function apply($param = [])
    {
        if (empty($param)) {
            return [
                FareMasterPricerTbSearch::FLIGHTOPT_PUBLISHED,
                FareMasterPricerTbSearch::FLIGHTOPT_UNIFARES,
                FareMasterPricerTbSearch::FLIGHTOPT_TICKET_AVAILABILITY_CHECK,
                FareMasterPricerTbSearch::FLIGHTOPT_ELECTRONIC_TICKET,
            ];
        }
        return $param;
    }

}