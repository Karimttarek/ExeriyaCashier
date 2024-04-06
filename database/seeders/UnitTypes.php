<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unitTypes = public_path('json/unit_types.json');
        $file =  json_decode(file_get_contents($unitTypes));
        foreach($file as $j)
            DB::table('unit_types')->insert([
                'code' => $j->code,
                'desc_en' => $j->desc_en,
                'desc_ar' => $j->desc_ar,
            ]);
    }
}
