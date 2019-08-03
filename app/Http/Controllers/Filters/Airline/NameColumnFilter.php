<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 6/18/2018
 * Time: 11:53 AM
 */

namespace App\Http\Controllers\Filters\Airline;

use App\Filters\FilterInterface;
use App\Http\Controllers\Filters\QueryFilterInterface;
use Jenssegers\Mongodb\Eloquent\Builder;

class NameColumnFilter implements QueryFilterInterface
{
    public  function  apply(Builder $builder,$value)
    {
        return $builder->where('name',$value)
            ->orWhere("name", "LIKE", "%$value%")
            ->orWhere("code", "LIKE", "%$value%")
            ->orWhere("designator", "LIKE", "%$value%")
            ->orWhere("country", "LIKE", "%$value%");
    }
}