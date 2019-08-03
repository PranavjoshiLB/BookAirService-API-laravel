<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/19/2018
 * Time: 9:37 PM
 */

namespace App\Model;


use App\Http\Controllers\Filters\QueryFilter;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class BaseModel extends Model
{
    use QueryFilter;

}