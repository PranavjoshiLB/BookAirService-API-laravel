<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 6/2/2018
 * Time: 10:52 AM
 */

namespace App\Http\Controllers;

use App\Model\Flight\CabinClass;

class CabinClassController extends ApiController
{
    /**
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function getAll()
    {
        return ok(CabinClass::all());
    }

    /**
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function groupedClasses()
    {
        return ok(CabinClass::group());
    }
}