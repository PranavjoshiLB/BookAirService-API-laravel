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

class Airport extends BaseModel
{
    use QueryFilter;

    /**
     * @var string
     */
    protected $filterPath = "Airport";

    /**
     * @var string
     */
    protected $collection = "airports";

    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    protected $fillable = ['icao', 'iata', 'name', 'city', 'state', 'country', 'elevation', 'lat', 'lon', 'tz'];

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function pullFromCache()
    {
        $airlines = Cache::remember("airports", 300, function () {
            return Airport::all();
        });

        return $airlines;
    }
}