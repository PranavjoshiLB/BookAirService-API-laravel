<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AirportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\App\Model\Flight\Airport::count() > 0) {
            return;
        }
        if (Storage::disk('local')->exists('airports.json')) {
            $file = Storage::disk('local')->get('airports.json');
            $contents = (new \Nathanmac\Utilities\Parser\Parser())->json($file);
            foreach ($contents as $row) {
                \App\Model\Flight\Airport::create($row);
            }
        }
    }

}
