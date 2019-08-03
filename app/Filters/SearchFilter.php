<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/20/2018
 * Time: 11:25 AM
 */

namespace App\Filters;

trait SearchFilter
{
    protected $reqOptions = [];

    /**
     * @param array $filters
     * @param array $params
     */
    public function applyFilters(array $filters, $applicableFilters = [], $path = null)
    {
        $filters = collect($filters);

        $filters->each(function ($value, $filterName) use ($applicableFilters, $path) {

            $class = $this->getFilterFor($filterName, $path);

            if (!$class instanceof NullFilter && in_array($filterName, $applicableFilters)) {
                if (isset($this->reqOptions[$class->key])) {
                    $this->reqOptions[$class->key][] = $class->apply($value);
                } else {
                    $this->reqOptions[$class->key] = $class->apply($value);
                }
            }
        });
        return $this;
    }

    /**
     * @param $name
     * @return string
     */
    public function createFilterDecorator($name, $path = null)
    {
        $name = str_replace(" ", '', ucfirst($name)) . "Filter";
        return __NAMESPACE__ . '\\' . $path . '\\' . $name;
    }

    /**
     * @param $name
     * @return NullFilter
     */
    public function getFilterFor($name, $path = null)
    {
        $filterClassName = $this->createFilterDecorator($name, $path);
        if (!class_exists($filterClassName)) {
            return new NullFilter;
        }
        return new $filterClassName();
    }

    /**
     * @return array
     */
    public function getRequestOptions()
    {
        return $this->reqOptions;
    }

}