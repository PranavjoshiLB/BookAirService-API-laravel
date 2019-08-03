<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/29/2018
 * Time: 3:37 PM
 */

namespace App\Exceptions;


use Amadeus\Client\Result;
use Exception;

class AmadeusServiceException extends Exception
{
    /**
     * @var Result
     */
    public $result;

    public $errors = [];

    public function __construct(Result $result)
    {

        $this->result = $result;
        $this->code = $result->status;
        $this->setErrors();
    }

    /**
     *
     */
    public function setErrors()
    {
        $text = [];
        $messages = $this->result->messages;
        if (is_array($messages)) {
            foreach ($messages as $message) {
                $text [] = $message->text;
            }
        }
        $this->errors = $text;
    }

    /**
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function getResponse()
    {
        return response([
            'msg' => 'Amadeus Webservice error',
            'errors' => $this->errors,
            'statusCode' => $this->result->status,
            'status' => false,
        ], 417);
    }

    /**
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

}