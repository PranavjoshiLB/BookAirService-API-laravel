<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/27/2018
 * Time: 4:33 PM
 */

namespace App\Filters\InformativePricing;


use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use App\Filters\FilterInterface;
use Carbon\Carbon;

class SegmentFilter implements FilterInterface
{
    public $key = "segments";

    /**
     * @param $param
     * @return array
     */
    public function apply($param)
    {
        $segments = [];
        if (!is_array($param)) {
            throw new \InvalidArgumentException('itinerary segment must be an array');
        }
        // Tattoos must be unique for each segment in an itinerary (ItemNumber)
        $tattoos = 1;
        foreach ($param as $itinerary) {
            $segments[] = $this->createItinerarySegment($itinerary, $tattoos);
            $tattoos++;
        }
        return $segments;
    }

    /**
     * @param array $data
     * @param int $tattoo
     * @return Segment
     */
    protected function createItinerarySegment(array $data, int $tattoo)
    {
        $dateOfDeparture = $data['dateOfDeparture'] . " " . $data['timeOfDeparture'];
        $dateOfArrival = $data['dateOfArrival'] . " " . $data['timeOfArrival'];

        return new Segment([
            'departureDate' => Carbon::createFromFormat('Y-m-d H:i', $dateOfDeparture),
            'arrivalDate' => Carbon::createFromFormat('Y-m-d H:i', $dateOfArrival),
            'from' => $data['departureLocation'],
            'to' => $data['arrivalLocation'],
            'marketingCompany' => $data['marketingCompany'],
            'operatingCompany' => $data['operatingCompany'],
            'flightNumber' => $data['flightNumber'],
            'bookingClass' => $data['bookingClass'],
            'segmentTattoo' => $tattoo,
            'groupNumber' => $data['groupNumber']
        ]);

    }
}