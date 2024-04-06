<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_types')->insert([
            'code' => 0,
            'desc_en' => 'Material',
            'desc_ar' => 'مادة خام',
        ]);
        DB::table('product_types')->insert([
            'code' => 1,
            'desc_en' => 'Product',
            'desc_ar' => 'منتج نهائي',
        ]);
    }
}
