<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/9/2018
 * Time: 5:26 PM
 */

namespace App\Service\Car\Struct;


class RateClass
{
    const  ALL = "ALL";
    const  CORPORATE = "COR";
    const  LEISURE = "LEI";
    public $criteriaSetType;

    public function __construct(string $criterialSetType = self::ALL)
    {
        $this->criteriaSetType = $criterialSetType;
    }
}