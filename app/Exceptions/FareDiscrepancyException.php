<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/15/2018
 * Time: 10:44 PM
 */

namespace App\Exceptions;


use Amadeus\Client\Result;

class FareDiscrepancyException extends AmadeusServiceException
{
    public $errors = "Price for this itinerary has changed";

    public function __construct(Result $result)
    {
        parent::__construct($result);
    }

    /**
     * @return array|mixed
     */
    public function errors()
    {
        return $this->errors;
    }
}