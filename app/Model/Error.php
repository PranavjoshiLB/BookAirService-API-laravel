<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 6/14/2018
 * Time: 4:21 PM
 */

namespace App\Model;

class Error extends BaseModel
{
    protected $collection = "error";

    protected $fillable = [
        'user_id',
        'module',
        'datetime',
        'message',
        'status',
        'error_type'
    ];

    const CREATED_AT = 'log_date';
}