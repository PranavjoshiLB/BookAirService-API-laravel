<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/7/2018
 * Time: 10:01 AM
 */

namespace App\Filters\FareMasterPricer;


use Amadeus\Client\RequestOptions\Fare\MPFeeId;
use App\Filters\FilterInterface;

class FeeIdFilter implements FilterInterface
{
    public $key = "feeIds";

    public function apply($param = [])
    {

        if (!empty($param)) {
            return $param;
        }
        return [
            new MPFeeId([
                'type' => MPFeeId::FEETYPE_FARE_FAMILY_INFORMATION, 'number' => 3
            ])
        ];
    }
}