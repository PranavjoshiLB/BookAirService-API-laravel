<?php

namespace App\Service\Car;


use Amadeus\Client;
use Amadeus\Client\Struct\BaseWsMessage;
use App\Service\Car\Struct\BeginDateTime;
use App\Service\Car\Struct\CarProviderIndicator;
use App\Service\Car\Struct\CityAirPortSelection;
use App\Service\Car\Struct\ComputeMarkups;
use App\Service\Car\Struct\CountryState;
use App\Service\Car\Struct\EndDateTime;
use App\Service\Car\Struct\LocationType;
use App\Service\Car\Struct\PickupDropoffInfo;
use App\Service\Car\Struct\PickupDropoffLocations;
use App\Service\Car\Struct\PickupDropoffTimes;
use App\Service\Car\Struct\PointOfReference;
use App\Service\Car\Struct\RateClass;
use App\Service\Car\Struct\SortingRule;
use App\Service\RequestOption\Car\CarRequestOption;
use App\Service\Car\Struct\StatusDetails;

class GeneralCarInfo extends BaseWsMessage
{
    /**
     * @var CarProviderIndicator
     */
    public $carProviderIndicator;
    /**
     * @var RateClass;
     */
    public $rateClass;
    /**
     * @var ComputeMarkups
     */
    public $computeMarkups;
    /**
     * @var SortingRule;
     */
    public $sortingRule;

    /**
     * @var PickupDropoffInfo;
     */
    public $pickupDropoffInfo;
    /**
     * @var CityAirPortSelection
     */
    public $cityAirportSelection;
    /**
     * @var CountryState
     */
    public $countryState;

    /**
     * GeneralCarInfo constructor.
     * @param CarRequestOption $params
     */

    public function __construct(CarRequestOption $params)
    {
        $this->loadCarProviderIndicator($params);
        $this->loadComputeMarkups($params);
        $this->loadRateClass($params);
        $this->loadSortingRule($params);
        $this->loadDropOffTimes($params);
        $this->loadCityAirportSelection($params);
    }

    /**
     *  Set car indicator
     *
     * @param CarRequestOption $params
     */
    public function loadCarProviderIndicator(CarRequestOption $params)
    {
        if ($params->carProviderIndicator != null) {
            $status = new StatusDetails($params->carProviderIndicator);
            $this->carProviderIndicator = new CarProviderIndicator($status);
            return;
        }
        $this->carProviderIndicator = new CarProviderIndicator(new StatusDetails());
    }

    /**
     * @param CarRequestOption $params
     */
    public function loadSortingRule(CarRequestOption $params)
    {
        if ($params->sortingRule != null) {
            $this->sortingRule = new SortingRule($params->sortingRule);
            return;
        }
        $this->sortingRule = new SortingRule();
    }

    /**
     * @param CarRequestOption $params
     */
    public function loadRateClass(CarRequestOption $params)
    {
        if ($params->rateClass != null) {
            $this->rateClass = new RateClass($params->rateClass);
            return;
        }
        $this->rateClass = new RateClass();
    }

    /**
     * @param CarRequestOption $params
     */
    public function loadComputeMarkups(CarRequestOption $params)
    {
        if ($params->computeMarkups != null) {
            $this->computeMarkups = new ComputeMarkups($params->computeMarkups);
            return;
        }
        $this->computeMarkups = new ComputeMarkups();
    }

    /**
     * @param CarRequestOption $params
     */
    public function loadDropOffTimes(CarRequestOption $params)
    {
        if ($params->beginTime) {
            $begin = new BeginDateTime($params->beginTime);
            $end = new EndDateTime($params->endTime);
            $pickupDropoffTimes = new PickupDropoffTimes($begin, $end);
            $pickupDropffInfo = new PickupDropoffInfo();
            $pickupDropffInfo->pickupDropoffTimes = $pickupDropoffTimes;
            // PickupDropoffInfo Wrapper  to hold location details
            $pickupDropoffInfo2 = new PickupDropoffInfo();
            // Using Zip
            $this->setZipPickupOptions($params, $pickupDropoffInfo2);
            // Using Amadeus city Code
            $this->setAmadeusPickupOption($params, $pickupDropoffInfo2);

            $pickupDropffInfo->pickupDropoffInfo = $pickupDropoffInfo2;
            $this->pickupDropoffInfo = $pickupDropffInfo;
        }

    }


    /**
     * Zip location code as  pickup
     * @param CarRequestOption $params
     * @param PickupDropoffInfo $pickUpInfo
     */
    private function setZipPickupOptions(CarRequestOption $params, PickupDropoffInfo &$info)
    {
        if ($params->locationType instanceof LocationType
            &&
            $params->pointOfReference instanceof PointOfReference
        ) {
            $info->pointOfReference = $params->pointOfReference;
            $info->locationType = $params->locationType;
        }
    }

    /**
     * Amadeus location code as  pickup
     * @param CarRequestOption $params
     * @param PickupDropoffInfo $pickUpInfo
     */
    private function setAmadeusPickupOption(CarRequestOption $params, PickupDropoffInfo &$pickUpInfo)
    {
        if ($params->locationType instanceof LocationType
            &&
            $params->locationDescription instanceof Client\Struct\Info\LocationDescription
        ) {
            $location = new PickupDropoffLocations($params->locationType, $params->locationDescription);
            $pickUpInfo->pickupDropoffLocations = $location;
        }
    }

    /**
     * @param CarRequestOption $params
     */
    public function loadCityAirportSelection(CarRequestOption $params)
    {
        if ($params->cityAirportSelection instanceof CityAirPortSelection) {
            $this->cityAirportSelection = $params->cityAirportSelection;
        }
        if ($params->countryState instanceof CountryState) {
            $this->countryState = $params->countryState;
        }
    }


}