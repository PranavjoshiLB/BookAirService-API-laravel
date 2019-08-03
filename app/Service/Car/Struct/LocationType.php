<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/12/2018
 * Time: 12:27 PM
 */

namespace App\Service\Car\Struct;


class LocationType
{

    const DEFAULT_TYPE = "PUP";
    /**
     * @var string
     */
    public $locationType;

    public function __construct($type = self::DEFAULT_TYPE)
    {
        $this->locationType = $type;
    }
}