<?php

namespace App\Services\Receipts;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ReceiptService{

    public function create($types = [] , $view){
//        $id = DB::table('receipts')->whereIn('receipt_type' ,$types)->max('no')+1;
        $id = DB::table('receipts')->whereIn('receipt_type' ,$types)->select(DB::raw("MAX(CAST(no AS UNSIGNED)) as id"))->pluck('id')[0]+1;
        $customers = DB::table('customers')->select('uuid' , 'tax_code' ,'name')->get();
        $expenses = DB::table('expenses')->select('code','name')->get();
        return view($view , compact('id','customers','expenses'));
    }

    public function store($request) : void{

        DB::table('receipts')->insert([
            'uuid' => Str::uuid(),
            'no' => $request->no,
            'receipt_type' => $request->receipt_type,
            'receipt_date' => $request->receipt_date,
            'statement' => $request->statement,
            'receiver_uuid' => $request->customer_uuid .$request->exp_code,
            'receiver_name' => $request->customer_name . $request->receiver_name .$request->exp_name,
            'check_no' => $request->check_no,
            'bank_name' => $request->bank_name,
            'value' => $request->value,
            'value_text' => $request->value_text,
        ]);
    }

    public function edit($uuid , $view){

        $receipt = DB::table('receipts')->where('uuid' ,$uuid)->select('uuid' ,'no','receipt_type' ,'receipt_date','statement',
            'exp_code','exp_name','receiver_uuid','receiver_name','bank_name','check_no','value','value_text')->get();
        $customers = DB::table('customers')->select('uuid' , 'tax_code' ,'name')->get();
        $expenses = DB::table('expenses')->select('code','name')->get();
        return view($view , compact('customers' ,'receipt','expenses'));
    }

    public function update($request, $uuid) : void{

        DB::table('receipts')->where('uuid' ,$uuid)->update([
            'receipt_type' => $request->receipt_type,
            'receipt_date' => $request->receipt_date,
            'statement' => $request->statement,
            'receiver_uuid' => $request->customer_uuid .$request->exp_code,
            'receiver_name' => $request->customer_name . $request->receiver_name .$request->exp_name,
            'check_no' => $request->check_no,
            'bank_name' => $request->bank_name,
            'value' => $request->value,
            'value_text' => $request->value_text,
        ]);

    }

    public function destroy($request) : void{

        foreach($request->item as $uuids){
            DB::table('receipts')->where('uuid' , $uuids)->delete();
        }
    }

    /**
     * Extra Methods
     */

    /**
     * @param int $number
     * @return string
     */
    public function numberFormatToText(int $number) : string{
        return LaravelLocalization::getCurrentLocale() == 'en'
            ? (new \NumberFormatter( LaravelLocalization::getCurrentLocale() , \NumberFormatter::SPELLOUT))->format($number) . ' Egyptian Pounds only'
            : (new \NumberFormatter( LaravelLocalization::getCurrentLocale() , \NumberFormatter::SPELLOUT))->format($number) . ' جنيها فقط لاغير .';
    }

    /**
     * @param $uuid
     * @param $view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function print($uuid,$view) {
        $company = DB::table('system_profile')->pluck('company_name');
        $receipt = DB::table('receipts')->where('uuid' ,$uuid)->select('uuid' ,'no' ,'receipt_date','statement','supplier_name','customer_name','receiver_name','bank_name','check_no','value','value_text')->get();
        return view($view , compact('receipt','company'));
    }

}
