<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyCodes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencyCodes = public_path('json/currency_code.json');
        $file =  json_decode(file_get_contents($currencyCodes));
        foreach($file as $j)
            DB::table('currency')->insert([
                'Code' => $j->code,
                'Desc_en' => $j->Desc_en,
            ]);
    }
}
