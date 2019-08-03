<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/12/2018
 * Time: 4:10 PM
 */

namespace App\Service\Flight\RequestOption;

use Amadeus\Client\RequestOptions\Pnr\Element\Address as ContactAddress;

class Address extends ContactAddress
{

    public function __construct(array $params = [])
    {
        parent::__construct($params);
        $this->setAddressLine($params);
        $this->setType();
    }

    /**
     *
     */
    public function setAddressLine($params)
    {
        if (isset($params['addressLine'])) {
            $this->addressLine1 = $params['addressLine'];
        }
    }

    /**
     *
     */
    public function setType()
    {
        $this->type = ContactAddress::TYPE_BILLING_STRUCTURED;
    }
}