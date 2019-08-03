<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/12/2018
 * Time: 11:59 AM
 */

namespace App\Http\Controllers;


use Amadeus\Client\Result;
use Amadeus\Client\Struct\PointOfRef\Search\Area;
use Amadeus\Client\Struct\PointOfRef\Search\BusinessId;
use App\Service\Car\CarService;
use App\Service\Car\Contracts\CarServiceInterface;
use App\Service\Car\Struct\CityAirPortSelection;
use App\Service\Car\Struct\ComputeMarkups;
use App\Service\Car\Struct\CountryState;
use App\Service\Car\Struct\LocationType;
use App\Service\Car\Struct\PointOfReference;
use App\Service\Car\Struct\RateClass;
use App\Service\Car\Struct\SortingRule;
use App\Service\Car\Struct\StatusDetails;
use App\Service\RequestOption\Car\CarRequestOption;
use Illuminate\Http\Request;

class CarController extends ApiController
{
    /**
     * @var CarService
     */
    protected $carService;

    public function __construct(CarServiceInterface $service)
    {
        $this->carService = $service;
    }
}