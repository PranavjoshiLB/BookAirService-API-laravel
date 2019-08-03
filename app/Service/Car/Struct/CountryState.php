<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/13/2018
 * Time: 10:59 AM
 */

namespace App\Service\Car\Struct;


class CountryState
{
    public $countryCode;

    public function __construct($code)
    {
        $this->countryCode = $code;
    }
}