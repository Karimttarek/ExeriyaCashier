<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Throwable;

class ReceiptsController extends Controller
{
    public function returnReceipts($types = [] , $view){
        $receipts = DB::table('receipts')->whereIn('receipt_type' , $types)
            ->orderBy('receipt_date','desc')
            ->select('uuid' ,'no' ,'receipt_date' ,'statement','supplier_name','receiver_name','customer_name', 'exp_name','value')->paginate(env('PAGINATE'));
        return view($view , compact('receipts'));
    }

    public function createReceipt($types = [] , $view){
        $id = DB::table('receipts')->whereIn('receipt_type' ,$types)->max('no')+1;
        $customers = DB::table('customers')->select('uuid' , 'tax_code' ,'name')->get();
        $expenses = DB::table('expenses')->select('code','name')->get();
        return view($view , compact('id','customers','expenses'));
    }

    public function storeReceipt($request , $route){
        try{
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
            return redirect()->route($route)->with('status' , __('app.SS'));
        }catch(Throwable $e){
            return redirect()->route($route)->with('error' , __('app.SWH'));
        }
    }

    public function editReceipt($uuid , string $view){
        $receipt = DB::table('receipts')->where('uuid' ,$uuid)->select('uuid' ,'no','receipt_type' ,'receipt_date','statement',
            'exp_code','exp_name','receiver_uuid','receiver_name','bank_name','check_no','value','value_text')->get();
        $customers = DB::table('customers')->select('uuid' , 'tax_code' ,'name')->get();
        $expenses = DB::table('expenses')->select('code','name')->get();
        return view($view , compact('customers' ,'receipt','expenses'));
    }

    public function updateReceipt($request,$uuid  ,$route){

        try{
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
            return redirect()->route($route)->with('status' , __('app.SS'));
        }catch(Throwable $e){
            return redirect()->route($route)->with('error' , __('app.SWH'));
        }
    }

    public function destroyReceipt($request , $route){
        foreach($request->item as $uuids){
            DB::table('receipts')->where('uuid' , $uuids)->delete();
        }

        return redirect()->route($route)->with('status',  __('app.DS'));
    }
    public function numberFormat($request){
        if($request->ajax()){
            if(LaravelLocalization::getCurrentLocale() == 'en'){
                $f = new \NumberFormatter( 'en', \NumberFormatter::SPELLOUT);
                return $f->format($request->no) . ' Only.';
            }elseif(LaravelLocalization::getCurrentLocale() == 'ar'){
                $f = new \NumberFormatter( 'ar', \NumberFormatter::SPELLOUT);
                return $f->format($request->no) . ' جنيها فقط لاغير . ';
            }

        }
    }
    public function printReceipt($uuid,$view){
        $company = DB::table('system_profile')->pluck('company_name');
        $receipt = DB::table('receipts')->where('uuid' ,$uuid)->select('uuid' ,'no' ,'receipt_date','statement','supplier_name','customer_name','receiver_name','bank_name','check_no','value','value_text')->get();
        return view($view , compact('receipt','company'));
    }

   public function insertAndPrintReceipt($request , $view){
       $id = DB::table('receipts')->insertGetId([
           'uuid' => Str::uuid(),
           'no' => $request->no,
           'receipt_type' => $request->receipt_type,
           'receipt_date' => $request->receipt_date,
           'statement' => $request->statement,
           'customer_uuid' => $request->customer_uuid,
           'customer_name' => $request->customer_name,
           'exp_code' => $request->exp_code,
           'exp_name' => $request->exp_name,
           'supplier_uuid' => $request->customer_uuid,
           'supplier_name' => $request->customer_name,
           'receiver_name' => $request->receiver_name,
           'check_no' => $request->check_no,
           'bank_name' => $request->bank_name,
           'value' => $request->value,
           'value_text' => $request->value_text,
       ]);
       $company = DB::table('system_profile')->pluck('company_name');
       $receipt = DB::table('receipts')->where('id' ,$id)->select('uuid' ,'no' ,'receipt_date','statement','supplier_name','customer_name','receiver_name','bank_name','check_no','value','value_text')->get();
       return view($view , compact('receipt','company'));
   }
}
