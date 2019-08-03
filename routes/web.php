<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/v1', function () {
    return response()->json(["Trajilis api v1.0"]);
});

// All routes are protected with api key

$router->group(['middleware' => ['api.key'], 'prefix' => 'v1'], function () use ($router) {

    //The following routes require authentication
    $router->group(['middleware' => 'auth'], function () use ($router) {
        // This route checks if requested flight segments are able to sell
        $router->post('/air/bookable/status', 'BookAirController@Bookable');

        $router->post('/air/book', 'BookAirController@bookFlight');
    });

    // Call this route for multi availability search
    $router->get('/air/multi/search', 'SearchAirController@searchMultiAvailability');
    // Api route for fare_masterPricerTravelBoardSearch
    $router->post('/air/search', 'SearchAirController@searchFareMasterBoard');
    // Api route for fare_InformativeBestPricing
    $router->post('/air/pricing', 'SearchAirController@fareInformativeBestPricing');

    // Get all currencies
    $router->get('currency', 'CurrencyController@getAll');

    // Get all airports including code
    $router->get('airport', 'AirPortController@search');

    // Get all available airlines
    $router->get('airline', 'AirlineController@search');


    // Get all cabin classes
    $router->get('air/cabin/class', 'CabinClassController@groupedClasses');

});
