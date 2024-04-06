<?php

namespace App\Http\Controllers\Stocktaking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class StocktakingController extends Controller
{
    public function index(){
        $data = DB::table('invoicehead')
            ->whereNull('deleted_at')
            ->whereNot('status' ,'Stocktaked')
            ->where('invoice_date' , '<=' ,now())
            ->select(
                DB::raw('COALESCE( SUM(CASE WHEN invoice_type IN (5) THEN total ELSE 0 END) ,0) as INCOME,
                    COALESCE( SUM(CASE WHEN invoice_type IN (6) THEN total ELSE 0 END) ,0 ) as OUTCOME'),
                DB::raw('COALESCE(SUM(CASE WHEN invoice_type IN (5) THEN total ELSE 0 END - CASE WHEN invoice_type IN (6) THEN  total ELSE 0 END ) ,0) as balance'),

                DB::raw('COUNT(CASE WHEN invoice_type IN (5) THEN id END) as sales_c , COUNT(CASE WHEN invoice_type IN (6) THEN id END) as return_c ')
                )
            ->get();

        $from_date = DB::table('invoicehead')
            ->whereNull('deleted_at')
            ->whereNot('status' ,'Stocktaked')
            ->whereIn('invoice_type' ,[5,6])
            ->orderBy('invoice_date')
            ->pluck('invoice_date')[0] ?? date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')));

        return view('stocktaking.index' ,compact('data','from_date'));
    }

    public function gard(Request $request){


        $bal = DB::table('invoicehead')
            ->where('invoice_date' , '>=' ,!empty($request->from_date) ? $request->from_date : '')
            ->where('invoice_date' , '<=' ,$request->to_date)
            ->whereNull('deleted_at')
            ->whereNot('status' ,'Stocktaked')
            ->pluck(
                DB::raw('COALESCE(SUM(CASE WHEN invoice_type IN (5) THEN total ELSE 0 END - CASE WHEN invoice_type IN (6) THEN  total ELSE 0 END ) ,0) as balance'),

            )[0];

            if ($bal == $request->balance) {

                DB::beginTransaction();
                try{
                    if ($bal != 0 && $request->balance != 0) {

                        // Update Status
                        DB::table('invoicehead')
                        ->whereIn('invoice_type' ,[5,6])
                        ->whereNot('status' ,'Stocktaked')
                        ->where('invoice_date' , '>=' ,!empty($request->from_date) ? $request->from_date : '')
                        ->where('invoice_date' , '<=' ,$request->to_date)
                        ->update([
                            'deleted_at' => now(),
                            'status' => 'Stocktaked',
                        ]);
                        // make Receipt
                        DB::table('receipts')->insert([
                            'uuid' => Str::uuid(),
                            'no' => DB::table('receipts')->where('receipt_type' ,13)->select(DB::raw("MAX(CAST(no AS UNSIGNED)) as id"))->pluck('id')[0]+1,
                            'receipt_type' => '13',
                            'receipt_date' => now(),
                            'statement' => 'جرد وردية بتاريخ ' . now(),
                            'receiver_uuid' => '0',
                            'receiver_name' => '0 / المركز الرئيسى',
                            'value' => $request->balance,
                            'value_text' =>  (new \NumberFormatter( LaravelLocalization::getCurrentLocale() , \NumberFormatter::SPELLOUT))->format($request->balance) . ' جنيها فقط لاغير .',
                        ]);

                        DB::commit();
                        return redirect()->route('stocktaking.index')->with('status' , __('app.Stocktaked'));
                    }else{
                        return redirect()->route('stocktaking.index')->with('warning' , __('app.There are no receipts to be stocktaked'));
                    }

                } catch (\Throwable $th) {
                    DB::rollBack();
                    return redirect()->route('stocktaking.index')->with('error', __('app.SWH'));
                }

            }else{
                return redirect()->route('stocktaking.index')->with('error', __('app.SWH'));
            }
    }


    /**
     * Filter
     */public function filter(Request $request){
        if($request->ajax()){

            return DB::table('invoicehead')
            ->where('invoice_date' , '>=' ,!empty($request->from_date) ? $request->from_date : '')
            ->where('invoice_date' , '<=' ,$request->to_date)
            ->whereNull('deleted_at')
            ->whereNot('status' ,'Stocktaked')
            ->select(
                DB::raw('COALESCE( SUM(CASE WHEN invoice_type IN (5) THEN total ELSE 0 END) ,0) as INCOME,
                    COALESCE( SUM(CASE WHEN invoice_type IN (6) THEN total ELSE 0 END) ,0 ) as OUTCOME'),
                DB::raw('COALESCE(SUM(CASE WHEN invoice_type IN (5) THEN total ELSE 0 END - CASE WHEN invoice_type IN (6) THEN  total ELSE 0 END ) ,0) as balance'),

                DB::raw('COUNT(CASE WHEN invoice_type IN (5) THEN id END) as sales_c , COUNT(CASE WHEN invoice_type IN (6) THEN id END) as return_c ')
                )
            ->get();

        }
     }
}
