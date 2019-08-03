<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/9/2018
 * Time: 5:23 PM
 */

namespace App\Service\Car\Struct;

/**
 * Class PickupDropoffTimes
 * @package App\Service\Car\Struct
 */
class PickupDropoffTimes
{
    /**
     * @var BeginDateTime
     */
    public $beginDateTime;

    /**
     * @var EndDateTime;
     */
    public $endDateTime;

    public function __construct(BeginDateTime $begin, EndDateTime $end)
    {
        $this->beginDateTime = $begin;
        $this->endDateTime = $end;
    }
}