<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 9:54 AM
 */

namespace App\Filters;


interface FilterInterface
{
    public function apply($param);
}