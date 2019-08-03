<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/31/2018
 * Time: 9:58 AM
 */

namespace App\Http\Controllers\Filters;


use Jenssegers\Mongodb\Eloquent\Builder;

interface QueryFilterInterface
{

    /**
     * @param Builder $builder
     * @param $value
     * @return mixed
     */
    public   function apply(Builder $builder,$value);
}