<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/23/2018
 * Time: 4:42 PM
 */

namespace App\Service\Flight\RequestOption;

use Amadeus\Client\RequestOptions\Pnr\Traveller as Passenger;


class Traveller extends Passenger
{

    public function __construct(array $params = [])
    {

        parent::__construct($params);
        if (isset($params['infant'])) {
            $this->loadInfant($params['infant']);
        }
    }

    /**
     * @param $infants
     */
    public function loadInfant($infant)
    {
        $infant['number'] = 1;
        $this->infant = new Passenger($infant);
    }
}