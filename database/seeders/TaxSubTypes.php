<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxSubTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taxTypes = public_path('json/tax_subtypes.json');
        $file =  json_decode(file_get_contents($taxTypes));
        foreach($file as $j)
            DB::table('tax_sub_types')->insert([
                'Code' => $j->Code,
                'Desc_en' => $j->Desc_en,
                'Desc_ar' => $j->Desc_ar,
                'TaxtypeReference' => $j->TaxtypeReference,
            ]);
    }
}
