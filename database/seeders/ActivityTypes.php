<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ActivityTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ac = public_path('json/activity_types.json');
        $file =  json_decode(file_get_contents($ac));
        foreach($file as $j)
            DB::table('activity_types')->insert([
                'code' => $j->code,
                'Desc_en' => $j->Desc_en,
                'Desc_ar' => $j->Desc_ar,
            ]);
    }
}
