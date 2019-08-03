<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/8/2018
 * Time: 4:29 AM
 */

namespace App\ViewModel;


use Carbon\Carbon;
use Robbo\Presenter\Presenter;

class MultiAvailabilityViewModel extends Presenter
{


    public function getDestination()
    {
        return $this->singleCityPairInfo['locationDetails']['origin'];
    }

    public function getOrigin()
    {
        return $this->singleCityPairInfo['locationDetails']['destination'];
    }

    /**
     * @return array
     */
    public function getFreeText()
    {
        $freeText = $this->singleCityPairInfo['cityPairFreeFlowText'];
        $freeTexts = [];
        foreach ($freeText as $entry) {
            $freeTexts[] = $entry['freeText'];
        }
        return $freeTexts;
    }

    /**
     * @return array
     */
    public function getAvailableFlights()
    {
        $flights = $this->singleCityPairInfo['flightInfo'];
        $data = [];
        foreach ($flights as $flight) {

            $flightBasicInfo = $flight['basicFlightInfo'];
            $flightClasses = $flight['infoOnClasses'];
            $flightAddInfo = $flight['additionalFlightInfo'];

            $data[] = [
                'departureDate' => $this->departureDate($flightBasicInfo['flightDetails']['departureDate']),
                'departureTime' => $this->departureTime($flightBasicInfo['flightDetails']['departureTime']),
                'arrivalDate' => $this->arrivalDate($flightBasicInfo['flightDetails']['arrivalDate']),
                'arrivalTime' => $this->arrivalTime($flightBasicInfo['flightDetails']['arrivalTime']),
                'departureLocation' => $flightBasicInfo['departureLocation']['cityAirport'],
                'arrivalLocation' => $flightBasicInfo['departureLocation']['cityAirport'],
                'company' => $flightBasicInfo['marketingCompany']['identifier'],
                'flightNumber' => $flightBasicInfo['flightIdentification']['number'],
                'serviceClass' => $this->flightServiceClass($flightClasses),
                'flightDetails' => $this->flightDetails($flightAddInfo['flightDetails']),
                'terminal' => isset($flightAddInfo['departureStation']) ? $flightAddInfo['departureStation']['terminal'] : null
            ];

        }
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function flightServiceClass(array $data)
    {
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function flightDetails(array $data)
    {
        return $data;
    }

    /**
     * @return mixed
     */
    public function presentDepartureDate()
    {
        return $this->singleCityPairInfo['departureDate'];
    }

    protected function departureDate(string $date)
    {
        $date = \DateTime::createFromFormat('dmy', $date);
        return $date->format('Y-m-d');
    }

    /**
     * @param string $date
     * @return string
     */
    protected function arrivalDate(string $date)
    {
        $date = \DateTime::createFromFormat('dmy', $date);
        return $date->format('Y-m-d');
    }

    /**
     * @param string $time
     * @return string
     */
    protected function arrivalTime(string $time)
    {
        $time = \DateTime::createFromFormat('Hi', $time);
        return $time->format('H:i');

    }

    /**
     * @param string $time
     * @return string
     */
    protected function departureTime(string $time)
    {
        $time = \DateTime::createFromFormat('Hi', $time);
        return $time->format('H:i');
    }

    /**
     * @return array
     */
    public function output()
    {
        return [
            'origin' => $this->getOrigin(),
            'destination' => $this->getDestination(),
            'freeText' => $this->getFreeText(),
            'flights' => $this->getAvailableFlights()
        ];
    }
}