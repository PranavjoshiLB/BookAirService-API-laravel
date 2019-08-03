<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 11:04 AM
 */

namespace App\Filters\FlightAvailability;


use Amadeus\Client\RequestOptions\Air\MultiAvailability\RequestOptions;
use App\Filters\FilterInterface;
use Carbon\Carbon;

class RequestOption implements FilterInterface
{
    public $key = "requestOptions";

    /**
     * @param $param
     * @return RequestOptions
     */
    public function apply($param)
    {

        return new RequestOptions([
            'departureDate' => Carbon::parse($param['departureDate']),
            'from' => $param['departureLocation'],
            'to' => $param['arrivalLocation'],
            'requestType' => RequestOptions::REQ_TYPE_NEUTRAL_ORDER
        ]);
    }
}