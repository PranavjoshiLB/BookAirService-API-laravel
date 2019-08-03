<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/9/2018
 * Time: 5:17 PM
 */

namespace App\Service\Car\Struct;


class StatusDetails
{

    const  ALL_CARS = "N";
    const LISTED_CARS = "Y";
    public $indicator = self::ALL_CARS;
}