<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 6/1/2018
 * Time: 4:38 PM
 */

namespace App\Http\Controllers;

use App\Model\Flight\Airline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AirlineController
{
    /**
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function getAll()
    {

        $airlines = Cache::remember('airlines', 36000, function () {
            return Airline::all();
        });

        return ok($airlines);
    }

    public function search(Request $request)
    {
        $filters = $request->all();
        $airlines = Airline::filterQuery($filters)->get();

        return ok($airlines);
    }
}