<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/10/2018
 * Time: 11:07 AM
 */

namespace App\Filters\PnrElement;


use App\Filters\FilterInterface;
use Amadeus\Client\RequestOptions\Pnr\Element\Address as ContactAddress;

class AddressFilter implements FilterInterface
{
    public $key = "elements";

    public function apply($param)
    {
        return new ContactAddress([
            'type' => ContactAddress::TYPE_BILLING_STRUCTURED,
            'company' => isset($param['company']) ? $param['company'] : null,
            'name' => $param['name'],
            'addressLine1' => $param['addressLine'],
            'city' => $param['city'],
            'country' => $param['country'],
            'zipCode' => $param['zipCode'],
        ]);

    }
}