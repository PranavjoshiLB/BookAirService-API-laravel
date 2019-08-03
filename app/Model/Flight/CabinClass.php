<?php

namespace App\Model\Flight;


class CabinClass
{

    /** Returns a collection of flight classes
     * @return \Illuminate\Support\Collection
     */
    public static function all()
    {
        return collect(
            [
                array('id' => 'A', 'name' => 'First Class '),

                array('id' => 'B', 'name' => 'Economy'),

                array('id' => 'C', 'name' => 'Business Class'),

                array('id' => 'D', 'name' => 'Business Class'),

                array('id' => 'E', 'name' => 'Economy Premium'),

                array('id' => 'F', 'name' => 'First Class'),

                array('id' => 'G', 'name' => 'Conditional Reservation'),

                array('id' => 'H', 'name' => 'Economy Class'),

                array('id' => 'I', 'name' => 'Discounted Business Class'),

                array('id' => 'J', 'name' => 'Business Class '),

                array('id' => 'K', 'name' => 'Discounted Economy Class'),

                array('id' => 'L', 'name' => 'Discounted Economy Class'),

                array('id' => 'M', 'name' => 'Economy Class'),

                array('id' => 'N', 'name' => 'Discounted Economy Class'),

                array('id' => 'O', 'name' => 'Discounted Economy Class'),

                array('id' => 'P', 'name' => 'First Class '),

                array('id' => 'Q', 'name' => 'Discounted Economy Class'),

                array('id' => 'R', 'name' => 'First Class Suite/Supersonic'),

                array('id' => 'S', 'name' => 'Discounted Economy Class'),

                array('id' => 'T', 'name' => 'Discounted Economy Class'),

                array('id' => 'U', 'name' => 'Discounted Economy Class'),

                array('id' => 'V', 'name' => 'Discounted Economy Class'),

                array('id' => 'W', 'name' => 'Economy Premium'),

                array('id' => 'X', 'name' => 'Discounted Economy Class'),

                array('id' => 'Y', 'name' => 'Economy Class'),

                array('id' => 'Z', 'name' => 'Discounted Business Class'),
            ]
        );
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public  static function group(){
      return  collect([
            ['name' => 'First Class', 'id' => ['A','F','P','R']],
            ['name' => 'Premium Economy', 'id' => ['E','W']],
            ['name' => 'Discounted Economy', 'id' => ['I','K','L','N','O','Q','S','T','U','V','X','T','S']],
            ['name' => 'Business Class', 'id' => ['C','D','J']],
            ['name' => 'Discounted Business Class', 'id' => ['I','Z']],
            ['name' => 'Economy Class', 'id' => ['B','H','M','Y']],

        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function find($id)
    {
        return CabinClass::all()->where('id', $id)->first();
    }
}