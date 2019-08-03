<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/31/2018
 * Time: 9:57 AM
 */

namespace App\Http\Controllers\Filters;


use App\Http\Controllers\Filters\QueryFilterInterface;
use Jenssegers\Mongodb\Eloquent\Builder;

class NullQueryFilter implements QueryFilterInterface
{
    public function apply(Builder $builder, $value)
    {
    }

}