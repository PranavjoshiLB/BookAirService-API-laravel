<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/27/2018
 * Time: 4:34 PM
 */

namespace App\Filters\InformativePricing;


use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
use App\Filters\FilterInterface;

class TravellerFilter implements FilterInterface
{
    public $key = "passengers";

    /**
     * @param $param
     * @return array
     */
    public function apply($param)
    {
        $passengers =$param;

        if(!$this->validatePassenger($passengers)){
            throw new \InvalidArgumentException('Passengers can not all be infants');
        }

        $travelers = [];
        $currentQuantity = 0;

        foreach ($passengers as $passenger) {
            $count = $passenger['count'];

            if ($passenger['type'] != Passenger::TYPE_INFANT) {
                $tattoos = $this->getTattoos($currentQuantity, $count);
                $travelers[] = $this->createPassenger($passenger['type'], $tattoos);
            } else {
                $travelers[] = $this->createPassenger($passenger['type'], [1]);
            }
            $currentQuantity += $count;
        }

        return $travelers;

    }

    /**
     *  Create passenger
     * @param string $type
     * @param array $tattoos
     * @return Passenger
     */
    protected function createPassenger(string $type, array $tattoos)
    {
        return new Passenger([
            'type' => $type,
            'tattoos' => $tattoos
        ]);
    }

    /**
     * @param int $min
     * @param int $max
     * @return array
     */
    public function getTattoos(int $currentQuantity, int $totalPassenger)
    {
        $total = $currentQuantity + $totalPassenger;
        $currentQuantity += 1;
        return range($currentQuantity, $total);
    }

    /**
     *  This validates that passengers are not all infants.
     * @param $passengers
     * @return bool
     */
    protected  function validatePassenger(array $passengers){
        //TODO
        return true;
    }

}