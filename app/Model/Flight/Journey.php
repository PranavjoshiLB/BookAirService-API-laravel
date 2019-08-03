<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/16/2018
 * Time: 9:38 PM
 */

namespace App\Model\Flight;


use App\Model\BaseModel;

class Journey extends BaseModel
{
    const ONE_WAY = 1;
    const ROUND_TRIP = 2;
    protected $collection = 'journeys';

    protected $fillable = [
        'departure_time',
        'departure_location',
        'departure_terminal',
        'arrival_location',
        'arrival_time',
        'company_id',
        'control_number'
    ];


}
