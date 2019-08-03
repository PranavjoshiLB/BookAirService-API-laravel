<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/12/2018
 * Time: 1:25 PM
 */

namespace App\Service\Car\Struct;


use Amadeus\Client\Struct\Info\LocationDescription;

class PickupDropoffLocations
{

    /**
     * @var string;
     */
    public $locationType;
    /**
     * @var LocationDescription;
     */
    public $locationDescription;

    public function __construct(LocationType $type=null, LocationDescription $description=null)
    {
        $this->locationType = $type;
        $this->locationDescription = $description;
    }
}