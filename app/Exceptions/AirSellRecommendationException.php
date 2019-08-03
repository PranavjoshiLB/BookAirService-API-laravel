<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/13/2018
 * Time: 11:55 AM
 */

namespace App\Exceptions;


use Amadeus\Client\Result;

class AirSellRecommendationException extends AmadeusServiceException
{

    public function __construct(Result $result)
    {
        parent::__construct($result);
    }
}