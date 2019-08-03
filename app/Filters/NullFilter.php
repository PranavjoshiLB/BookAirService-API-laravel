<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 11:41 AM
 */

namespace App\Filters;


class NullFilter implements FilterInterface
{
    public $key = "nothing";

    public function apply($param)
    {
    }
}