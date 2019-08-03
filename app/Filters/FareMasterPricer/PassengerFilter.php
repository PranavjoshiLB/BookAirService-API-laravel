<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 10:23 AM
 */

namespace App\Filters\FareMasterPricer;


use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use App\Filters\FilterInterface;

class PassengerFilter implements FilterInterface
{
    public $key = "passengers";

    public function apply($passengers)
    {
        // If passenger was not set, use default parameters
        if ($passengers == null) {
            return new MPPassenger([
                'type' => MPPassenger::TYPE_ADULT,
                'count' => 1
            ]);
        }

        $Mpassenger = [];

        foreach ($passengers as $passenger)
            $Mpassenger[] = new MPPassenger([
                'type' => $passenger['type'],
                'count' => $passenger['count']
            ]);

        return $Mpassenger;
    }
}