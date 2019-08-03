<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/31/2018
 * Time: 10:31 AM
 */

namespace App\Http\Controllers\Filters\Airport;


use App\Http\Controllers\Filters\QueryFilterInterface;
use Jenssegers\Mongodb\Eloquent\Builder;

class CountryColumnFilter implements QueryFilterInterface
{

    /**
     * @param Builder $builder
     * @param $value
     */
    public function apply(Builder $builder, $value)
    {
        return $builder->where('country',$value)->orWhere("country", "LIKE", "%$value%");
    }

}