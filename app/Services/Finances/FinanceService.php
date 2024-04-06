<?php

namespace App\Services\Finances;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class FinanceService{

    /**
     * @param $types
     * @param $view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function create($type , $view , string $table){
        $id = DB::table('receipts')->where('receipt_type' ,$type)->select(DB::raw("MAX(CAST(no AS UNSIGNED)) as id"))->pluck('id')[0]+1;
        $expenses = DB::table($table)->select('code','name')->get();
        return view($view , compact('id','expenses'));
    }

    /**
     * @param $request
     * @return void
     */
    public function store($request , $type , $heading = ''): void{
        $uuid = Str::uuid();

        try {
            DB::table('receipts')->insert([
                'uuid' => $uuid,
                'no' => $request->no,
                'receipt_type' => $type,
                'receipt_date' => $request->receipt_date,
                'statement' => $request->statement,
                'receiver_name' => $heading ?: $type = 5 ? 'مصروفات' : 'ايرادات',
                'value' => $request->value,
                'value_text' => $request->value_text,
            ]);
            foreach ($request->exp as $e){
                DB::table('receipt_details')->insert([
                    'uuid' => $uuid,
                    'no' => $request->no,
                    'receipt_type' => $type,
                    'receipt_date' => $request->receipt_date,
                    'type_id' => $e['exp_code'],
                    'type_name' => $e['exp_name'],
                    'statement' => $e['exp_statement'],
                    'value' => $e['exp_val'],
                    'created_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')))

                ]);
            }

        }catch (Throwable $e){
            DB::rollBack();
        }
    }

    /**
     * @param $uuid
     * @param $view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit($uuid , $view , string $table){
        $receipt = DB::table('receipts')->where('uuid' ,$uuid)->select('uuid' ,'no','receipt_type' ,'receipt_date','statement',
            'value','value_text')->get();

        $expenses = DB::table($table)->select('code','name')->get();
        $current_expenses = DB::table('receipt_details')->where('uuid', $uuid)->select('type_id' , 'type_name','statement','value')->get();
        return view($view , compact('receipt','expenses','current_expenses'));
    }

    /**
     * @param $uuid
     * @param $request
     * @return void
     */
    public function update($request ,$uuid ,$type): void{

        DB::table('receipts')->where('uuid' ,$uuid)->update([
            'receipt_date' => $request->receipt_date,
            'statement' => $request->statement,
            'value' => $request->value,
            'value_text' => $request->value_text,
        ]);

        DB::table('receipt_details')->where('uuid' ,$uuid)->delete();
        foreach ($request->exp as $e){
            DB::table('receipt_details')->insert([
                'uuid' => $uuid,
                'no' => $request->no,
                'receipt_type' => $type,
                'receipt_date' => $request->receipt_date,
                'type_id' => $e['exp_code'],
                'type_name' => $e['exp_name'],
                'statement' => $e['exp_statement'],
                'value' => $e['exp_val'],
                'created_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')))

            ]);
        }
    }


}
