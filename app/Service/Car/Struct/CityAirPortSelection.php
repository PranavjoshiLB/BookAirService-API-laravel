<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/13/2018
 * Time: 10:53 AM
 */

namespace App\Service\Car\Struct;


class CityAirPortSelection
{
    /**
     * The 3 letter IATA city or airport code
     * @var $airportOrCityCode
     */
    public $cityOrAirportTag;
    /**
     * the 2 letter IATA country code (or state for the countries that have states), vicinity identification code and car provider code
     * @var LocationInfo;
     */
    public $locationInfo;



    public function __construct(string $airportCityCode, string $cityorAirportTag)
    {
        $this->locationInfo = new LocationInfo($airportCityCode);
        $this->cityOrAirportTag = $cityorAirportTag;
    }
}