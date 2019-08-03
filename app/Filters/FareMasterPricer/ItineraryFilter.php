<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 10:12 AM
 */

namespace App\Filters\FareMasterPricer;

use Amadeus\Client\RequestOptions\Fare\MPDate;
use Amadeus\Client\RequestOptions\Fare\MPItinerary;
use Amadeus\Client\RequestOptions\Fare\MPLocation;
use Amadeus\Client\Struct\InvalidArgumentException;
use App\Filters\FilterInterface;
use Carbon\Carbon;

class ItineraryFilter implements FilterInterface
{
    public $key = 'itinerary';

    public function apply($itinerary)
    {
        $data = [];
        if (! is_array($itinerary)) {
            throw new \InvalidArgumentException('Argument for Itinerary filter must be an array');
        }
        foreach ($itinerary as $param) {

            $dateTime = [
                'dateTime' => Carbon::parse($param['departureDate']),
                ];

            if(isset($param['timeWindow'])){
                $dateTime['rangeMode'] =MPDate::RANGEMODE_PLUS;
                $dateTime['range'] =0;
                $dateTime['timeWindow'] =(int) $param['timeWindow'];

            }
            $data[] = new MPItinerary([
                'departureLocation' => new MPLocation(['city' => $param['departureLocation']]),
                'arrivalLocation' => new MPLocation(['city' => $param['arrivalLocation']]),
                'nrOfConnections' => isset($param['numOfStop']) ? $param['numOfStop'] : null,
                'date' => new MPDate($dateTime),
            ]);
        }

        return $data;
    }
}