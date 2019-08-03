<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 10:51 AM
 */

namespace App\Filters\FareMasterPricer;

use App\Filters\FilterInterface;

class TotalResultFilter implements FilterInterface
{
    public $key = "nrOfRequestedResults";

    public function apply($values = null)
    {
        if (!is_null($values)) {
            return $values;
        }
        return 250;
    }
}