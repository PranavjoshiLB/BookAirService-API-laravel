<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/9/2018
 * Time: 5:20 PM
 */

namespace App\Service\Car\Struct;


class ComputeMarkups
{
    const WITHOUT_MARKUP = "N";
    const WITH_MARKUP = "Y";
    public $actionRequestCode;

    /**
     * ComputeMarkups constructor.
     * @param string $actionRequestCode
     */
    public function __construct(string $actionRequestCode =self::WITHOUT_MARKUP)
    {
        $this->actionRequestCode =$actionRequestCode;
    }
}