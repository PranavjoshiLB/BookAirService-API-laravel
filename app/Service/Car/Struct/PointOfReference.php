<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/12/2018
 * Time: 1:18 PM
 */

namespace App\Service\Car\Struct;


use Amadeus\Client\Struct\PointOfRef\Search\Area;
use Amadeus\Client\Struct\PointOfRef\Search\BusinessId;

class PointOfReference
{
    /**
     * @var BusinessId
     */
    public $businessId;
    /**
     * @var Area
     */
    public $area;

    public function __construct(BusinessId $businessId, Area $area)
    {
        $this->businessId = $businessId;
        $this->area = $area;
    }
}