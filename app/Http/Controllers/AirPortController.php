<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/15/2018
 * Time: 6:51 AM
 */

namespace App\Http\Controllers;


use App\Model\Flight\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AirPortController extends ApiController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function search(Request $request)
    {
        $filters =$request->all();
        $airport= Airport::filterQuery($filters)->get();
        return ok($airport);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAll(Request $request)
    {
        if ($request->has('page')) {

            $airports = Airport::paginate(50);
            $airports['status'] = true;
            $airports['statusCode'] = Response::HTTP_OK;

            return response()->json($airports);
        }

        $airports = Cache::remember('airports', 36000, function () {
            return Airport::all();
        });
        return response()->json($airports);
    }
}