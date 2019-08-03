<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/7/2018
 * Time: 10:56 PM
 */

namespace App\Filters\PnrElement;

use Amadeus\Client\RequestOptions\Pnr\Element\Contact;


use App\Filters\FilterInterface;

class ContactFilter implements FilterInterface
{
    public $key = "elements";

    public function apply($param)
    {

        $contacts=[] ;
        if (isset($param['email'])) {
            $contacts[] = new Contact([
                'type' => Contact::TYPE_EMAIL,
                'value' => $param['email']
            ]);
        }
        if (isset($param['phone'])) {
            $contacts[] = new Contact([
                'type' => Contact::TYPE_PHONE_MOBILE,
                'value' => $param['phone']
            ]);
        }
        return $contacts;
    }
}