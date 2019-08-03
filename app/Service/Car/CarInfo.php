<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/9/2018
 * Time: 10:06 AM
 */

namespace App\Service\Car;


use Amadeus\Client\Struct\BaseWsMessage;
use App\Service\RequestOption\Car\CarRequestOption;

class CarInfo extends BaseWsMessage
{
    /**
     * @var GeneralCarInfo
     */
    protected $generalInfo;

    /**
     * CarInfo constructor.
     * @param CarRequestOption $option
     */
    public function __construct(CarRequestOption $option)
    {
        $this->generalInfo = new GeneralCarInfo($option);

    }
}