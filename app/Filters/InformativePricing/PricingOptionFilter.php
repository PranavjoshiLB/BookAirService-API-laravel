<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/28/2018
 * Time: 9:15 AM
 */

namespace App\Filters\InformativePricing;


use Amadeus\Client\RequestOptions\Fare\InformativePricing\PricingOptions;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use App\Filters\FilterInterface;

class PricingOptionFilter implements FilterInterface
{
    public $key = 'pricingOptions';

    /**
     * This filter applies override option based on user request
     * @param $param
     * @return PricingOptions
     */
    public function apply($param=null)
    {
        return new PricingOptions([
            'overrideOptions' => [
                PricingOptions::OVERRIDE_FARETYPE_PUB,
                PricingOptions::OVERRIDE_FARETYPE_UNI,
                //PricingOptions::OVERRIDE_FARETYPE_CORPUNI
            ],
            'currencyOverride' => 'USD'
        ]);
    }
}