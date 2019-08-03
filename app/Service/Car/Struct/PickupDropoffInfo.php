<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/12/2018
 * Time: 12:26 PM
 */

namespace App\Service\Car\Struct;


use Amadeus\Client\Struct\Info\LocationDescription;

class PickupDropoffInfo
{
    /**
     * @var string
     */
    //public $locationType;
    /**
     * @var IataAirportLocation []
     */
    //public $iataAirportLocations = [];

    /**
     * @var LocationDescription/
     */
    //public $iataAirportCityLocationCode;

    /**
     * @var PointOfReference
     */
    //public $pointOfReference;

    /**
     * @var PickupDropoffLocations []
     */
    //public $pickupDropoffLocations = [];

    /**
     * @var PickupDropoffTimes
     */
    //  public $pickupDropoffTimes;
    /**
     * @var array
     */
    //    public $pickupDropoffInfo;


    public function __set($property, $value)
    {
        // if (property_exists($this, $property)) {
        $this->$property = $value;
        //}
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}