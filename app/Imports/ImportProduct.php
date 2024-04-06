<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportProduct implements ToCollection , WithStartRow
{

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection  $rows){
        $rows->each(function ($row){
            if (!Product::where('name' ,$row[2])->exists()){
                $uuid = Str::uuid();
                DB::beginTransaction();

                DB::table('products')->insert([
                    'uuid' => $uuid,
                    'code_type' => 'product', // a
                    // 'item_code' => $row[0] == 'EGS'
                    //     ? str_contains($row[1] ,'EG-') ? $row[1] : config('eta.registration_number').$row[1]
                    //     : $row[1] , // c
                    'item_code' => $row[0],
                    'bar_code' => $row[1],
                    'name' => $row[2], // C
                    'name_ar' => $row[3], // D
                    'description' => $row[4],
                    'description_ar' => $row[5], // F

                    'type_code' => $row[6], // G
                    'type_desc' => $row[6], // H

                    'purchase_price' => $row[8],
                    'sell_price' => $row[9],

                    'item_type' => 1, // I

                    'first_unit_type' => $row[6], // J
                    'first_unit_qty' => $row[7], // K
                    'first_unit_pur_price' => $row[8], // L
                    'first_unit_sell_price' => $row[9], // M

                    'second_unit_type' => $row[10], // J
                    'second_unit_qty' => $row[11], // K
                    'second_unit_pur_price' => $row[12], // L
                    'second_unit_sell_price' => $row[13], // M

                    'third_unit_type' => $row[14], // J
                    'third_unit_qty' => $row[15], // K
                    'third_unit_pur_price' => $row[16], // L
                    'third_unit_sell_price' => $row[17], // M


                    'currency_code' => 'EGP', // L
                    'currency_desc' => 'Egyptian Pound', // M


                    'active_from' => date('Y-m-d' , strtotime(now())), // P
                    'active_to' => date('Y-m-d' , strtotime(now())), // Q
                    'entry' => Auth::user()->name,
                    'created_at' => Carbon::now(),
                ]);

                DB::table('manufacturs')->insert([
                    'parent_uuid' => $uuid,
                    'parent_name' => $row[2],
                    'child_uuid' => $uuid,
                    'child_name' => $row[2],
                    'price' => $row[8],
                    'qty' => 1,
                    'number' => 0,
                    'created_at' => Carbon::now(),
                ]);
                DB::commit();
            }else{
                DB::beginTransaction();

                DB::table('products')
                    ->where('name' ,$row[2])->update([
                        'code_type' => 'product', // a
                        // 'item_code' => $row[0] == 'EGS'
                        //     ? str_contains($row[1] ,'EG-') ? $row[1] : config('eta.registration_number').$row[1]
                        //     : $row[1] , // c
                        'item_code' => $row[0],
                        'bar_code' => $row[1],
                        'name' => $row[2], // C
                        'name_ar' => $row[3], // D
                        'description' => $row[4],
                        'description_ar' => $row[5], // F

                        'type_code' => $row[6], // G
                        'type_desc' => $row[6], // H

                        'purchase_price' => $row[8],
                        'sell_price' => $row[9],

                        'item_type' => 1, // I

                        'first_unit_type' => $row[6], // J
                        'first_unit_qty' => $row[7], // K
                        'first_unit_pur_price' => $row[8], // L
                        'first_unit_sell_price' => $row[9], // M

                        'second_unit_type' => $row[10], // J
                        'second_unit_qty' => $row[11], // K
                        'second_unit_pur_price' => $row[12], // L
                        'second_unit_sell_price' => $row[13], // M

                        'third_unit_type' => $row[14], // J
                        'third_unit_qty' => $row[15], // K
                        'third_unit_pur_price' => $row[16], // L
                        'third_unit_sell_price' => $row[17], // M


                        'currency_code' => 'EGP', // L
                        'currency_desc' => 'Egyptian Pound', // M

                        'updated_at' => Carbon::now(),
                    ]);

                DB::table('manufacturs')
                    ->where('parent_name' , $row[2])
                    ->update([
                    'parent_name' => $row[2],
                    'price' => $row[8]
                ]);

                DB::commit();
            }
        });
    }

}
