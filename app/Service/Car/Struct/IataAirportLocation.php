<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/12/2018
 * Time: 12:31 PM
 */

namespace App\Service\Car\Struct;


use Amadeus\Client\Struct\Info\LocationDescription;

class IataAirportLocation
{
    /**
     * @var LocationDescription
     */
    public $locationDescription;
}