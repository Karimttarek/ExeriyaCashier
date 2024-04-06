<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(){
        $data = DB::table('products')
            ->select('products.name','products.name_ar','products.stock as stStock',
                DB::raw('CASE WHEN invoicedetails.invoice_type IN(1,4) THEN qty END as INCOME, CASE WHEN invoicedetails.invoice_type IN(2,3) THEN qty END as OUTCOME'))
            ->leftJoin('invoicedetails' , 'invoicedetails.item_uuid' , '=' , 'products.uuid')
            ->groupBy('invoicedetails.qty')
            ->paginate(env('PAGINATE'));
//return $data;
        return view('inventory.inventory' , compact('data'));
    }

}
