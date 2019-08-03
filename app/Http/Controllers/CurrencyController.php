<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/15/2018
 * Time: 6:51 AM
 */

namespace App\Http\Controllers;


use App\Model\Currency;
use Illuminate\Support\Facades\Cache;

class CurrencyController extends ApiController
{

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {

        $currencies = Cache::remember('currencies', 36000, function () {
            return Currency::all();
        });
        return ok($currencies);
    }
}