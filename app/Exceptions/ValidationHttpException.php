<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/29/2018
 * Time: 3:28 PM
 */

namespace App\Exceptions;


use Dingo\Api\Exception\ResourceException;
use Exception;

class ValidationHttpException extends ResourceException
{
    const ERR_TYPE = "Validation Error";

    public function __construct($errors = null, Exception $previous = null, $headers = [], $code = 0)
    {
        parent::__construct(self::ERR_TYPE, $errors, $previous, $headers, $code);
    }

}