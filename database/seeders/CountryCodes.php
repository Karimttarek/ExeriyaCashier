<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryCodes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countryCodes = public_path('json/country_code.json');
        $file =  json_decode(file_get_contents($countryCodes));
        foreach($file as $j)
            DB::table('countries')->insert([
                'Code' => $j->code,
                'Desc_en' => $j->Desc_en,
                'Desc_ar' => $j->Desc_ar,
            ]);
    }
}
