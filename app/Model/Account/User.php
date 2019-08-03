<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/18/2018
 * Time: 12:08 PM
 */

namespace App\Model\Account;

use App\Model\BaseModel;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends BaseModel
{

    protected $collection = "user";

    /**
     * @param $id
     * @return mixed
     */
    /**public static function find($id)
    {
        return User::where('_id', $id)->first();
    }**/
}
