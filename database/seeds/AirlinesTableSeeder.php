<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AirlinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\App\Model\Flight\Airline::count() > 0) {
            return;
        }
        if (Storage::disk('local')->exists('airlines.json')) {
            $file = Storage::disk('local')->get('airlines.json');
            $contents = (new \Nathanmac\Utilities\Parser\Parser())->json($file);
            foreach ($contents as $row) {
                $row['logo_small'] = 'logos/airlines/100x100/'.$row['designator'].".png";
                $row['logo'] = 'logos/airlines/600x600/'.$row['designator'].".png";
                \App\Model\Flight\Airline::create($row);
            }
        }
    }
}
