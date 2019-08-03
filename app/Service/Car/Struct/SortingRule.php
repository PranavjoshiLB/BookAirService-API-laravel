<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/9/2018
 * Time: 5:28 PM
 */

namespace App\Service\Car\Struct;


class SortingRule
{
    const STANDARD = "STD";
    const BEST = "BST";
    const WIDE_RANGE = "WRV";

    public $actionRequestCode;

    /**
     *
     * SortingRule constructor.
     * @param string $actionCode
     */
    public function __construct(string $actionCode = self::STANDARD)
    {
        $this->actionRequestCode = $actionCode;
    }

}