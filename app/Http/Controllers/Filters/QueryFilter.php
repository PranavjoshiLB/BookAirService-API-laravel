<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/31/2018
 * Time: 9:57 AM
 */

namespace App\Http\Controllers\Filters;


use Jenssegers\Mongodb\Eloquent\Builder;

trait QueryFilter
{
    /**
     * @param array $filters
     * @param Builder $query
     */
    public function applyFilters(Builder $query, array $filters)
    {
        $filters = collect($filters);
        $filters->each(function ($value, $filterName) use ($query) {
            $this->getFilterFor($filterName)->apply($query, $value);
        });
    }

    /**
     * @param $name
     * @param $folder
     * @return string
     */
    public function createFilterDecorator($name)
    {

        $name = str_replace(" ", '', ucfirst($name)) . 'ColumnFilter';
        if (!is_null($this->filterPath)) {
            return __NAMESPACE__ . '\\' . ucfirst($this->filterPath) . '\\' . $name;
        }
        return __NAMESPACE__ . '\\' . $name;

    }

    /**
     * @param $name
     * @param null $folder
     * @return NullQueryFilter
     */
    public function getFilterFor($name)
    {
        $filterClassName = $this->createFilterDecorator($name);
        if (!class_exists($filterClassName)) {
            return new NullQueryFilter();
        }
        return new $filterClassName();
    }

    /**
     * @param $query
     * @param array $filters
     */
    public function scopeFilterQuery($query, array $filters)
    {
        return $this->applyFilters($query, $filters);
    }
}