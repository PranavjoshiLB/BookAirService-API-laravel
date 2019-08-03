<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/29/2018
 * Time: 3:44 PM
 */

namespace App\Exceptions;


use Amadeus\Client\Exception;

class ApiKeyException extends Exception
{
    public $status = 401;

    public function __construct($message = "", $code = null, \Exception $previous = null)
    {
    }

    public function getResponse()
    {
        return response()->json([
            'msg' => 'x-api-key header is not set',
            'statusCode' => $this->status,
            'status' =>false
        ], $this->status);
    }

}