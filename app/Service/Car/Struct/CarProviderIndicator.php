<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/9/2018
 * Time: 5:17 PM
 */

namespace App\Service\Car\Struct;



class CarProviderIndicator
{
    /**
     * @var StatusDetails
     */
    public $statusDetails;

    public  function __construct(StatusDetails $status)
    {
        $this->statusDetails =$status;
    }

}