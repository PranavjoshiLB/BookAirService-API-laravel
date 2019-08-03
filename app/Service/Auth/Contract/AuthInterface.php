<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/19/2018
 * Time: 12:37 PM
 */

namespace App\Service\Auth\Contract;


interface AuthInterface
{

    /**
     *  This access and get authentication token from the user
     * @return mixed
     */
    public  function login();

    /**
     *  This validate authentication token from this request
     * @param $token
     * @return mixed
     */
    public function validate($token);

    /**
     * This expires authenticated token
     * @param $token
     * @return mixed
     */
    public function invalidate($token);

    /**
     * @return mixed
     */
    public function logout();
}