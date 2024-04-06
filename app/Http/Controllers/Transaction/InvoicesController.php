<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class InvoicesController extends Controller
{

    public function returnInvoices($invoice_type , $view , $eInvoice = false , $eInvoiceDirection){
        $invoices = DB::table('invoicehead')
            ->where('invoice_type' , $invoice_type)
            ->orderBy('invoice_date' , 'desc')
            ->orderBy('created_at' , 'desc')
            ->select('uuid','id','internal_id','submission_uuid','document_uuid','invoice_date','customer_id','customer_name','issuer_name','total_tax','total_discount','total','items_count','status')->paginate(env('INVPAGINATE'));

        $auth = DB::table('invoice_portal_auth')->select('client_id','client_secret','pin_code')->get();

        if($eInvoice == true && count($auth) > 0){
                $access_token = app('App\Http\Controllers\Auth\InvoicePortalController')->index();
                $response = Http::withHeaders([
                    'Authorization' => $access_token,
                    'Content-Type' => 'application/json',
                ])->get(env('PRDapiBaseUrl').'api/v1.0/documents/recent?direction=' . $eInvoiceDirection);

            return view($view , compact('invoices' ,'response'));
        }else{
            return view($view , compact('invoices'));
        }

    }

    public function searchInvoice($invoice_type ,$number ,$field = ''){
        $invoice = DB::table('invoicehead')
            ->where('invoice_number' , 'LIKE' ,'%'.$number.'%')
            ->orWhere('invoicehead.'.$field , 'LIKE' ,'%'.$number.'%')
            ->where('invoice_type' , $invoice_type)
            ->orderBy('invoice_date' , 'desc')
            ->orderBy('created_at' , 'desc')
            ->select('uuid','id','internal_id','submission_uuid','document_uuid','invoice_date','customer_id','customer_name','issuer_name','total_tax','total_discount','total','items_count','status')
            ->paginate(env('INVPAGINATE'));
        return $invoice;
    }

    public function createInvoice($view){
        $id = Carbon::now()->format('Ymd').random_int(100000, 999999);
        $products = DB::table('products')->select('uuid','name','name_ar','sell_price','sell_price','tax','discount','stock')->get();
        $taxTypes = DB::table('tax_types')->select('Code','Desc_en' ,'Desc_ar')->get();
        $countries = DB::table('countries')->select('code','Desc_en','Desc_ar')->get();
        $governorates = DB::table('governorates')->select('id','Desc_en','Desc_ar')->get();
        $cities = DB::table('cities')->select('governorate_id','Desc_en','Desc_ar')->get();
        $profile = DB::table('system_profile')->select('company_name','tax_rCode','tax_aCode')->get();
        $branches = DB::table('branches')->select('branch_name','country','governorate','city','building_number','street')->get();
        $customers = DB::table('customers')->select('tax_code','name')->get();
        $unit = DB::table('unit_types')->select('code' ,'desc_en' ,'desc_ar')->get();
        $currency = DB::table('currency')->select('code' ,'Desc_en')->get();
        return view($view, compact('id','countries' ,'governorates' ,'cities' , 'profile','branches','products','taxTypes','customers','unit','currency'));
    }

    public function returnInvoice($uuid,$view){

        $products = DB::table('products')->select('uuid','name','name_ar','sell_price','sell_price','tax','discount','stock')->get();
        $taxTypes = DB::table('tax_types')->select('Code','Desc_en' ,'Desc_ar')->get();
        $countries = DB::table('countries')->select('code','Desc_en','Desc_ar')->get();
        $governorates = DB::table('governorates')->select('id','Desc_en','Desc_ar')->get();
        $cities = DB::table('cities')->select('governorate_id','Desc_en','Desc_ar')->get();
        $customers = DB::table('customers')->select('tax_code','name')->get();
        $unit = DB::table('unit_types')->select('code' ,'desc_en' ,'desc_ar')->get();
        $currency = DB::table('currency')->select('code' ,'Desc_en')->get();
        $branches = DB::table('branches')->select('branch_name','country','governorate','city','building_number','street')->get();
        $invoicehead = DB::table('invoicehead')->where('uuid' , $uuid)->select([
            'id','uuid','internal_id','invoice_number','invoice_type','invoice_date','document_type','document_version',
            'issuer_id','issuer_type','issuer_name','issuer_country','issuer_gov','issuer_city','issuer_building_number','issuer_street','issuer_email','issuer_mobile',
            'customer_id','customer_type','customer_name','customer_country','customer_gov','customer_city','customer_building_number','customer_street',
            'invoice_discount','invoice_tax','total_tax_table',
            'total_sales','total_net','total_items','total_items_discount','total_tax','total_discount','total_after_discount','discount_after_tax','total','items_count','status','notes',
            'entry','is_cash','branch_name',
        ])->get();
        $invoicedetails = DB::table('invoicedetails')->where('invoicedetails.uuid' , $uuid)
            ->leftJoin('products' ,'products.uuid' ,'=' ,'invoicedetails.item_uuid')
            ->select(['products.name','products.name_ar','products.description','products.description_ar',
            'invoicedetails.id','invoicedetails.item_uuid','invoicedetails.code_type','invoicedetails.item_code','invoicedetails.item','invoicedetails.description',
                'invoicedetails.qty','invoicedetails.unit_type','invoicedetails.price','invoicedetails.currency','invoicedetails.tax','invoicedetails.tax_per','tax_table','tax_table_per',
                'invoicedetails.tax_type','invoicedetails.tax_sub_type','invoicedetails.taxvalue','invoicedetails.taxPervalue','invoicedetails.discount',
                'invoicedetails.discount_per','invoicedetails.net','invoicedetails.total_sales','invoicedetails.discount_after_tax','invoicedetails.total','invoicedetails.number'
        ])->get();
        return view($view, compact('countries' ,'governorates' ,'cities' ,'invoicehead','invoicedetails' ,'products' ,'taxTypes','customers','unit' ,'currency','branches'));
    }

    public function print($uuid,$view){
        $invoicehead = DB::table('invoicehead')->where('uuid' , $uuid)->select([
            'id','uuid','internal_id','invoice_type','invoice_date',
            'issuer_id','issuer_name','customer_id','customer_name',
            'invoice_discount','invoice_tax','total_tax_table',
            'total_sales','total_net','total_items','total_items_discount','total_tax','total_discount','total_after_discount','discount_after_tax','total','items_count'
        ])->get();

        $invoicedetails = DB::table('invoicedetails')->where('invoicedetails.uuid' , $uuid)
            ->leftJoin('products' ,'products.uuid' ,'=' ,'invoicedetails.item_uuid')
            ->select(['products.name','products.name_ar','products.description','products.description_ar',
                'invoicedetails.id','invoicedetails.item_uuid','invoicedetails.code_type','invoicedetails.item_code','invoicedetails.item','invoicedetails.description',
                'invoicedetails.qty','invoicedetails.unit_type','invoicedetails.price','invoicedetails.currency','invoicedetails.tax','invoicedetails.tax_per','tax_table','tax_table_per',
                'invoicedetails.tax_type','invoicedetails.tax_sub_type','invoicedetails.taxvalue','invoicedetails.taxPervalue','invoicedetails.discount',
                'invoicedetails.discount_per','invoicedetails.net','invoicedetails.total_sales','invoicedetails.discount_after_tax','invoicedetails.total','invoicedetails.number'
            ])->get();
        $company = DB::table('system_profile')->select('company_name','tax_rCode')->get();
        $profile = DB::table('branches')->select('mobile','street');
        return view($view, compact('invoicehead','invoicedetails' ,'company' , 'profile'));
    }

    public function destroyInvoice($request ,$invoice_type,$route){
        try{
            foreach($request->item as $uuids){
                DB::beginTransaction();
                $ids = DB::table('invoicehead')->where(['uuid' => $uuids ,'invoice_type' => $invoice_type])->pluck('internal_id');
                DB::table('invoicehead')->where(['uuid' => $uuids ,'invoice_type' => $invoice_type])->delete();
                DB::table('invoicedetails')->where('uuid' , $uuids)->delete();
                DB::table('receipts')->where('uuid' , $uuids)->delete();
                DB::table('receipts')->where('no' , $ids )->whereIn('receipt_type' , [1,2,8,9])->delete();
                DB::commit();
            }
        } catch(Throwable $e){
            report($e);
            DB::rollBack();
        }
        return redirect()->route($route)->with('status' ,__('app.DS'));
    }

    private function insertCustomer($Crequest){
        if (!Customer::where('name' , $Crequest->customer_name)->exists()){
            DB::table('customers')->insert([
                'uuid' => Str::uuid(),
                'type' => $Crequest->customer_type,
                'name'=> $Crequest->customer_name,
                'tax_code'=> $Crequest->customer_id,
                'country'=> $Crequest->customer_country,
                'gov'=> $Crequest->customer_gov,
                'city'=> $Crequest->customer_city,
                'building_number'=> $Crequest->customer_building_number,
                'street'=> $Crequest->customer_street,
            ]);
        }
    }

    public function storeInvoice( $request , $Crequest , $uuid , $invoice_type,$receipt_type ,$cash_receipt_type,$route , $route2 ){
        $uuid = Str::uuid();
        $request->validate([
            'invoice_number' => ['max:255', 'unique:invoicehead,invoice_number'],
            'internal_id' => ['required', 'max:255', 'unique:invoicehead,internal_id'],
        ]);

        // return $request;
        $system = DB::table('system_profile')->select('company_name', 'tax_rCode' ,'tax_aCode')->get();
        $branch = DB::table('branches')->where('branch_name' , $request->branch_name)->select('country','governorate','city','building_number','street')->get();
        try{
            DB::beginTransaction();

           if( in_array($invoice_type , array(1,3) ) ){
               DB::table('invoicehead')->insert([
                   'uuid' => $uuid,
                   'invoice_type' => $invoice_type ,
                   'invoice_number' => $request->internal_id,
                //    'invoice_date' => $request->invoice_date,
                   'invoice_date' => date("Y-m-d H:i", strtotime($request->invoice_date)),
                   'internal_id' => $request->internal_id,
                   'document_type' => $request->document_type,
                   'document_version' => $request->document_version,
                   'taxpayer_activity_code' => $system[0]->tax_aCode,

                   'issuer_id' => $Crequest->customer_id,
                   'issuer_type' => $Crequest->customer_type,
                   'issuer_name' => $Crequest->customer_name,
                   'issuer_country' => $Crequest->customer_country,
                   'issuer_gov' => $Crequest->customer_gov,
                   'issuer_city' => $Crequest->customer_city,
                   'issuer_building_number' => $request->customer_building_number,
                   'issuer_street' => $Crequest->customer_street,

                   'customer_id'=> $system[0]->tax_rCode,
                   'customer_type'=> 'B',
                   'customer_name'=> $system[0]->company_name,
                   'customer_country'=> $branch[0]->country,
                   'customer_gov'=> $branch[0]->governorate,
                   'customer_city'=> $branch[0]->city,
                   'customer_building_number'=> $branch[0]->building_number,
                   'customer_street'=> $branch[0]->street,

                   'invoice_discount' => $request->invoice_discount,
                   'invoice_tax' => $request->invoice_tax,

                   'total_sales' => $request->totalSales,
                   'total_net' => $request->totalNet,
                   'total_items' => $request->transTotalItems,
                   'total_items_discount' => $request->transTotalItemsDisc,
                   'total_tax' => $request->transTax,
                   'total_tax_table' => $request->transTaxTable,
                   'total_discount' => $request->transDisc,
                   'discount_after_tax' => $request->transTotalDiscAfterTax ,
                   'total' => $request->transTotal,
                   'notes' => $request->notes,
                   'items_count' => $request->itemsCount,
                   'entry' => Auth::user()->name,
                   'is_cash' => $request->is_cash,
                   'branch_name' => $request->branch_name,
                   'created_at' => Carbon::now(),
               ]);
           }elseif (in_array($invoice_type , array(2,4) ) ){
               DB::table('invoicehead')->insert([
                   'uuid' => $uuid,
                   'invoice_type' => $invoice_type ,
                   'invoice_number' => $request->internal_id,
                   'invoice_date' => date("Y-m-d H:i", strtotime($request->invoice_date)),
                   'internal_id' => $request->internal_id,
                   'document_type' => $request->document_type,
                   'document_version' => $request->document_version,
                   'taxpayer_activity_code' =>  $system[0]->tax_aCode,

                   'issuer_id' => $system[0]->tax_rCode,
                   'issuer_type' => 'B',
                   'issuer_name' =>  $system[0]->company_name,
                   'issuer_country' => $branch[0]->country,
                   'issuer_gov' => $branch[0]->governorate,
                   'issuer_city' => $branch[0]->city,
                   'issuer_building_number' => $branch[0]->building_number,
                   'issuer_street' => $branch[0]->street,

                   'customer_id'=> $Crequest->customer_id,
                   'customer_type'=> $Crequest->customer_type,
                   'customer_name'=> $Crequest->customer_name,
                   'customer_country'=> $Crequest->customer_country,
                   'customer_gov'=> $Crequest->customer_gov,
                   'customer_city'=> $Crequest->customer_city,
                   'customer_building_number'=> $Crequest->customer_building_number,
                   'customer_street'=> $Crequest->customer_street,

                   'invoice_discount' => $request->invoice_discount,
                   'invoice_tax' => $request->invoice_tax,

                   'total_sales' => $request->totalSales,
                   'total_net' => $request->totalNet,
                   'total_items' => $request->transTotalItems,
                   'total_items_discount' => $request->transTotalItemsDisc,
                   'total_tax' => $request->transTax,
                   'total_tax_table' => $request->transTaxTable,
                   'total_discount' => $request->transDisc,
                   'discount_after_tax' => $request->transTotalDiscAfterTax ,
                   'total' => $request->transTotal,
                   'notes' => $request->notes,
                   'items_count' => $request->itemsCount,
                   'entry' => Auth::user()->name,
                   'is_cash' => $request->is_cash,
                   'branch_name' => $request->branch_name,
                   'created_at' => Carbon::now(),
               ]);
           }

            if(!empty($request->items)){
                foreach($request->items as  $item){
                    if(!empty($item['taxable'])){
                        for ($i=0; $i < count($item['taxable'])-1 ; $i++);
                        DB::table('invoicedetails')->insert([
                            'uuid' => $uuid,
                            'invoice_number' => $request->internal_id,
                            'invoice_type' => $invoice_type,
                            'item_uuid' => $item['uuid'],
                            'item' => $item['item'],
                            'description' => $item['description'],
                            'code_type' => $item['code_type'],
                            'item_code' => $item['item_code'],
                            'qty' => $item['qty'],
                            'unit_type' => $item['unit_type'],
                            'price' => $item['unitPrice'],
                            'currency' => $item['currency'],
                            'tax' => $item['tax'],
                            'tax_per' => $item['taxPer'],
                            'tax_type' => implode(',',$item['taxable'][$i]['tax_type']) ,
                            'tax_sub_type' => implode(',',$item['taxable'][$i]['tax_sub_type']),
                            'taxvalue' => implode(',' ,$item['taxable'][$i]['taxvalue']) ,
                            'taxPervalue' => implode(',',$item['taxable'][$i]['taxPervalue']) ,

                            'tax_table' => $item['tax_table'],
                            'tax_table_per' => $item['tax_table_per'],

                            'discount' => $item['disc'],
                            'discount_per' => $item['discPer'],
                            'net' => $item['net'],
                            'total_sales' => $item['totalSales'],
                            'discount_after_tax' => $item['discountAfterTax'],
                            'total' => $item['total'],
                            'number' => $item['number'],
                            'created_at' => $request->invoice_date
                        ]);
                    }else{
                        DB::table('invoicedetails')->insert([
                            'uuid' => $uuid,
                            'invoice_number' => $request->internal_id,
                            'invoice_type' => $invoice_type,
                            'item_uuid' => $item['uuid'],
                            'item' => $item['item'],
                            'description' => $item['description'],
                            'code_type' => $item['code_type'],
                            'item_code' => $item['item_code'],
                            'qty' => $item['qty'],
                            'unit_type' => $item['unit_type'],
                            'price' => $item['unitPrice'],
                            'currency' => $item['currency'],
                            'tax' => $item['tax'],
                            'tax_per' => $item['taxPer'],

                            'tax_table' => $item['tax_table'],
                            'tax_table_per' => $item['tax_table_per'],

                            'discount' => $item['disc'],
                            'discount_per' => $item['discPer'],
                            'net' => $item['net'],
                            'total_sales' => $item['totalSales'],
                            'discount_after_tax' => $item['discountAfterTax'],
                            'total' => $item['total'],
                            'number' => $item['number'],
                            'created_at' => $request->invoice_date
                        ]);
                    }
                    // Update product Prices
                    if($invoice_type == 1){
                        DB::table('products')->where('uuid',$item['uuid'])->update([
                            'purchase_price' => $item['unitPrice']
                        ]);
                    }elseif ($invoice_type == 2){
                        DB::table('products')->where('uuid',$item['uuid'])->update([
                            'sell_price' => $item['unitPrice']
                        ]);
                    }

                }
            }
            Self::insertCustomer($Crequest);
            DB::table('receipts')->insert([
                'uuid' =>  $uuid,
                'no' => DB::table('receipts')->where('receipt_type' ,$receipt_type)->max('no')+1,
                'receipt_type' => $receipt_type,
                'receipt_date' => $request->invoice_date,
                'statement' => $request->internal_id,
                'supplier_uuid' => $request->customer_id,
                'supplier_name' => $request->customer_name,
                'value' => $request->transTotal,
            ]);
            if($request->is_cash == 1){
                DB::table('receipts')->insert([
                    'uuid' =>  Str::uuid(),
                    'no' => $request->internal_id,
                    'receipt_type' => $cash_receipt_type,
                    'receipt_date' => $request->invoice_date,
                    'statement' => $request->internal_id,
                    'supplier_uuid' => $request->customer_id,
                    'supplier_name' => $request->customer_name,
                    'value' => $request->transTotal,
                    'value_text' => (new \NumberFormatter( 'ar', \NumberFormatter::SPELLOUT))->format($request->transTotal)
                ]);
            }
            DB::commit();

            return redirect()->route($route)->with('status', __('app.SS'));
        }catch(Throwable $e){
            DB::rollBack();
            report($e);
            return redirect()->route($route2)->with('error', __('app.SWH'));
        }
    }

    public function updateInvoice( $request , $Crequest,$uuid , $newUuid , $invoice_type,$receipt_type ,$route , $route2){
        $newUuid = Str::uuid();
        $request->validate([
            'invoice_number' => 'unique:invoicehead,invoice_number,'.$request->internal_id,
            'internal_id' => 'required', 'unique:invoicehead,internal_id,'.$request->internal_id,
        ]);

        $system = DB::table('system_profile')->select('company_name', 'tax_rCode' ,'tax_aCode')->get();
        $branch = DB::table('branches')->where('branch_name' , $request->branch_name)->select('country','governorate','city','building_number','street')->get();

        try{
            DB::beginTransaction();

           if(in_array($invoice_type , array(1,3))){
               DB::table('invoicehead')->where('uuid' , $uuid)->update([
                   'uuid' => $newUuid,
                   'invoice_type' => $invoice_type ,
                   'invoice_number' => $request->internal_id,
                   'invoice_date' => date("Y-m-d H:i", strtotime($request->invoice_date)),
                   'internal_id' => $request->internal_id,
                   'document_type' => $request->document_type,
                   'document_version' => $request->document_version,

                   'issuer_id' => $Crequest->customer_id,
                   'issuer_type' => $Crequest->customer_type,
                   'issuer_name' => $Crequest->customer_name,
                   'issuer_country' => $Crequest->customer_country,
                   'issuer_gov' => $Crequest->customer_gov,
                   'issuer_city' => $Crequest->customer_city,
                   'issuer_building_number' => $request->customer_building_number,
                   'issuer_street' => $Crequest->customer_street,

                   'customer_id'=> $system[0]->tax_rCode,
                   'customer_type'=> 'B',
                   'customer_name'=> $system[0]->company_name,
                   'customer_country'=> $branch[0]->country,
                   'customer_gov'=> $branch[0]->governorate,
                   'customer_city'=> $branch[0]->city,
                   'customer_building_number'=> $branch[0]->building_number,
                   'customer_street'=> $branch[0]->street,

                   'invoice_discount' => $request->invoice_discount,
                   'invoice_tax' => $request->invoice_tax,

                   'total_sales' => $request->totalSales,
                   'total_net' => $request->totalNet,

                   'total_items' => $request->transTotalItems,
                   'total_items_discount' => $request->transTotalItemsDisc,
                   'total_tax' => $request->transTax,
                   'total_tax_table' => $request->transTaxTable,
                   'total_discount' => $request->transDisc,

                   'discount_after_tax' => $request->transTotalDiscAfterTax ,
                   'total' => $request->transTotal,
                   'notes' => $request->notes,
                   'items_count' => $request->itemsCount,
                   'is_cash' => $request->is_cash,
                   'branch_name' => $request->branch_name,
               ]);
           }elseif (in_array($invoice_type , array(2,4) ) ){
               DB::table('invoicehead')->where('uuid' , $uuid)->update([
                   'uuid' => $newUuid,
                   'invoice_type' => $invoice_type ,
                   'invoice_number' => $request->internal_id,
                   'invoice_date' => date("Y-m-d H:i", strtotime($request->invoice_date)),
                   'internal_id' => $request->internal_id,
                   'document_type' => $request->document_type,
                   'document_version' => $request->document_version,

                   'issuer_id' => $system[0]->tax_rCode,
                   'issuer_type' => 'B',
                   'issuer_name' =>  $system[0]->company_name,
                   'issuer_country' => $branch[0]->country,
                   'issuer_gov' => $branch[0]->governorate,
                   'issuer_city' => $branch[0]->city,
                   'issuer_building_number' => $branch[0]->building_number,
                   'issuer_street' => $branch[0]->street,

                   'customer_id'=> $Crequest->customer_id,
                   'customer_type'=> $Crequest->customer_type,
                   'customer_name'=> $Crequest->customer_name,
                   'customer_country'=> $Crequest->customer_country,
                   'customer_gov'=> $Crequest->customer_gov,
                   'customer_city'=> $Crequest->customer_city,
                   'customer_building_number'=> $Crequest->customer_building_number,
                   'customer_street'=> $Crequest->customer_street,

                   'invoice_discount' => $request->invoice_discount,
                   'invoice_tax' => $request->invoice_tax,

                   'total_sales' => $request->totalSales,
                   'total_net' => $request->totalNet,

                   'total_items' => $request->transTotalItems,
                   'total_items_discount' => $request->transTotalItemsDisc,
                   'total_tax' => $request->transTax,
                   'total_tax_table' => $request->transTaxTable,
                   'total_discount' => $request->transDisc,

                   'discount_after_tax' => $request->transTotalDiscAfterTax ,
                   'total' => $request->transTotal,
                   'notes' => $request->notes,
                   'items_count' => $request->itemsCount,
                   'is_cash' => $request->is_cash,
                   'branch_name' => $request->branch_name,
               ]);
           }

            if(!empty($request->items)){
                DB::table('invoicedetails')
                    ->where('uuid' ,$uuid)
                    ->delete();
                foreach($request->items as  $item){
                    if(isset($item['taxable'])){

                        for ($i=0; $i < count($item['taxable']) -1 ; $i++);
                            DB::table('invoicedetails')->insert([
                                'uuid' => $newUuid,
                                'invoice_number' => $request->internal_id,
                                'invoice_type' => $invoice_type,
                                'item_uuid' => $item['uuid'],
                                'item' => $item['item'],
                                'description' => $item['description'],
                                'code_type' => $item['code_type'],
                                'item_code' => $item['item_code'],
                                'qty' => $item['qty'],
                                'unit_type' => $item['unit_type'],
                                'price' => $item['unitPrice'],
                                'currency' => $item['currency'],
                                'tax' => $item['tax'],
                                'tax_per' => $item['taxPer'],
                                'tax_type' => implode(',',$item['taxable'][$i]['tax_type']) ,
                                'tax_sub_type' => implode(',',$item['taxable'][$i]['tax_sub_type']),
                                'taxvalue' => implode(',',$item['taxable'][$i]['taxvalue']) ,
                                'taxPervalue' => implode(',',$item['taxable'][$i]['taxPervalue']) ,

                                'tax_table' => $item['tax_table'],
                                'tax_table_per' => $item['tax_table_per'],

                                'discount' => $item['disc'],
                                'discount_per' => $item['discPer'],
                                'net' => $item['net'],
                                'total_sales' => $item['totalSales'],
                                'discount_after_tax' => $item['discountAfterTax'],
                                'total' => $item['total'],
                                'number' => $item['number'],
                                'created_at' => $request->invoice_date
                            ]);
                    }else{
                        DB::table('invoicedetails')->insert([
                            'uuid' => $newUuid,
                            'invoice_number' => $request->internal_id,
                            'invoice_type' => $invoice_type,
                            'item_uuid' => $item['uuid'],
                            'item' => $item['item'],
                            'description' => $item['description'],
                            'code_type' => $item['code_type'],
                            'item_code' => $item['item_code'],
                            'qty' => $item['qty'],
                            'unit_type' => $item['unit_type'],
                            'price' => $item['unitPrice'],
                            'currency' => $item['currency'],
                            'tax' => $item['tax'],
                            'tax_per' => $item['taxPer'],

                            'tax_table' => $item['tax_table'],
                            'tax_table_per' => $item['tax_table_per'],

                            'discount' => $item['disc'],
                            'discount_per' => $item['discPer'],
                            'net' => $item['net'],
                            'total_sales' => $item['totalSales'],
                            'discount_after_tax' => $item['discountAfterTax'],
                            'total' => $item['total'],
                            'number' => $item['number'],
                            'created_at' => $request->invoice_date
                        ]);
                    }
                    // Update product Prices
                    if($invoice_type == 1){
                        DB::table('products')->where('uuid',$item['uuid'])->update([
                            'purchase_price' => $item['unitPrice']
                        ]);
                    }elseif ($invoice_type == 2){
                        DB::table('products')->where('uuid',$item['uuid'])->update([
                            'sell_price' => $item['unitPrice']
                        ]);
                    }
                }
            }else{
                // return $request->internal_id;
                DB::table('invoicedetails')->where('invoice_number' , $request->internal_id )->delete();
            }
            Self::insertCustomer($Crequest);
            DB::table('receipts')->where('uuid' , $uuid)->delete();
            DB::table('receipts')->insert([
                'uuid' =>  $newUuid,
                'no' => DB::table('receipts')->where('receipt_type' ,$receipt_type)->max('no')+1,
                'receipt_type' => $receipt_type,
                'receipt_date' => $request->invoice_date,
                'statement' => "Invoice #".$request->internal_id,
                'supplier_uuid' => $request->customer_id,
                'supplier_name' => $request->customer_name,
                'value' => $request->transTotal,
            ]);

            DB::commit();

            return redirect()->route($route)->with('status', __('app.US'));
        }catch(Throwable $e){
            DB::rollBack();
//            report($e);
        //    return $e;
            return redirect()->route($route2,$uuid)->with('error', __('app.SWH'));
        }
    }
}
