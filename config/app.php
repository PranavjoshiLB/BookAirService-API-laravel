<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/7/2018
 * Time: 7:09 AM
 */
return [
    'providers' => [
        Jenssegers\Mongodb\MongodbServiceProvider::class
        ],
    'aliases' => [
        'Moloquent' => 'Jenssegers\Mongodb\Model',
    ],
    'api_key' => 's1HFf5ES8ZrUQBFs2UeiMtg6oDWGpf5nfPTFwVefrXyahbvQOh',
    'name' => 'Trajilis',
    'email' => 'samuel.james@alphahill.com',
    'locale' => env('APP_LOCALE', 'en'),
    
];