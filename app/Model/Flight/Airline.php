<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 5/14/2018
 * Time: 3:10 PM
 */

namespace App\Model\Flight;

use App\Http\Controllers\Filters\QueryFilter;
use App\Model\BaseModel;
use Illuminate\Support\Facades\Cache;

class Airline extends BaseModel
{
    use QueryFilter;

    protected $filterPath = "Airline";

    protected $collection = "airlines";

    protected $fillable = [
        'name',
        'designator',
        'code',
        'country',
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function pullFromCache()
    {
        $airlines = Cache::remember("airlines", 300, function () {
            return Airline::all();
        });

        return $airlines;
    }

    /**
     * @param $value
     * @return string
     */
    public function getLogoAttribute($value)
    {
        return url($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function getLogoSmallAttribute($value)
    {
        return url($value);
    }
}