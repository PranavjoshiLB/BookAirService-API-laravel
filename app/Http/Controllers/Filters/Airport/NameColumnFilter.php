<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/31/2018
 * Time: 10:25 AM
 */

namespace App\Http\Controllers\Filters\Airport;


use App\Http\Controllers\Filters\QueryFilterInterface;
use Jenssegers\Mongodb\Eloquent\Builder;

class NameColumnFilter implements QueryFilterInterface
{
    public  function  apply(Builder $builder,$value)
    {
        return $builder->where('name',$value)
            ->orWhere("name", "LIKE", "%$value%")
            ->orWhere("iata","LIKE","%$value%")
            ->orWhere("city","LIKE","%$value%");
    }

}