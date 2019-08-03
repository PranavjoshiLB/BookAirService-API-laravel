<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/8/2018
 * Time: 1:53 PM
 */

namespace App\ViewModel;


use Robbo\Presenter\Presenter;

class MasterPricerBoardViewModel extends AbstractAirline
{

    public  function __construct($object)
    {
        parent::__construct($object);
    }

    /**
     *
     */
    protected function getCurrency()
    {
        return $this->conversionRate['conversionRateDetail']['currency'];
    }

    protected function getFlightGroup()
    {
        return $this->flightIndex['groupOfFlights'];
    }


    /**
     * @return mixed
     */
    protected function getRecommendation()
    {
        return $this->recommendation;
    }

    /**
     * @param string $date
     */
    protected function formatDate(string $date)
    {
        $date = \DateTime::createFromFormat('dmy', $date);
        return $date->format('Y-m-d');
    }

    /**
     * @param string $date
     */
    protected function formatTime(string $time)
    {
        $time = \DateTime::createFromFormat('Hi', $time);
        return $time->format('H:i');
    }

    /**
     *
     */
    protected function getFormattedData()
    {
        $flights = $this->getFlightGroup();
        $paxFareDetail = $this->getRecommendation()['paxFareProduct']['paxFareDetail'];
        $totalAmount = $paxFareDetail['totalFareAmount'];
        $totalTaxAmount = $paxFareDetail['totalTaxAmount'];
        $company = $paxFareDetail['codeShareDetails']['company'];
        $description = $this->getRecommendation()['paxFareProduct']['fare'];

        $data = [];
        foreach ($flights as $flight) {

            $flightInfo = $flight['flightDetails']['flightInformation'];
            $date = $flightInfo['productDateTime'];
            $data[] = [
                'departureDate' => $this->formatDate($date['dateOfDeparture']),
                'departureTime' => $this->formatTime($date['timeOfDeparture']),
                'arrivalDate' => $this->formatDate($date['dateOfArrival']),
                'arrivalTime' => $this->formatTime($date['timeOfArrival']),
                'company' => $company,
                'flightNumber' => $flightInfo['flightOrtrainNumber'],
                'electronicTicketing' => $flightInfo['addProductDetail']['electronicTicketing'] == 'Y' ? true : false,
                'totalAmount' => $totalAmount,
                'totalTaxAmount' => $totalTaxAmount,
                'ticketDescription' => $description,
                'currency' => $this->getCurrency(),
                'location' => $flightInfo['location']
            ];
        }
        return $data;
    }

    /**
     * Returns filtered data
     * @return array
     */
    public function output()
    {
        return $this->getFormattedData();
    }
}