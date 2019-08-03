<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/13/2018
 * Time: 10:56 AM
 */

namespace App\Service\Car\Struct;


class LocationInfo
{

    public $airportOrCityCode;

    public function __construct($airportOrCityCode)
    {
        $this->airportOrCityCode = $airportOrCityCode;
    }
}