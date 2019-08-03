<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\App\Model\Currency::count() > 0) {
            return;
        }
        if (Storage::disk('local')->exists('currencies.json')) {
            $file = Storage::disk('local')->get('currencies.json');
            $contents = (new \Nathanmac\Utilities\Parser\Parser())->json($file);
            foreach ($contents as $row) {
                \App\Model\Currency::create($row);
            }
        }
    }
}
