<?php

namespace App\Services\Invoices;

use App\Enums\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PosReceiptService{


    /**
     * @param $view
     * @param $invoice_type
     * @throws \Exception
     */
    public function create($view = "pos.create" ,$invoice_type = Transaction::POS_RECEIPT ){
        return view($view ,$this->returnInvoiceData($invoice_type) );
    }


    public function store($request ,$invoice_type = Transaction::POS_RECEIPT) : void{

        $uuid = Str::uuid();

        DB::beginTransaction();

            DB::table('invoicehead')->insert($this->returnSalesInvoiceHeadData($uuid ,$invoice_type ,$request));

            if(!empty($request->items)) {
                $this->storeInvoiceItems($request, $uuid, $invoice_type);
            }
        DB::commit();
    }

    public function edit($uuid ,$view,$invoice_type = Transaction::POS_RECEIPT){

        $invoicehead = DB::table('invoicehead')->where('uuid' , $uuid)->select([
            'invoice_date','uuid','internal_id','invoice_number','document_uuid','total','items_count','notes',
        ])->get();
        $invoicedetails = DB::table('invoicedetails')->where('invoicedetails.uuid' , $uuid)
            ->leftJoin('products' ,'products.uuid' ,'=' ,'invoicedetails.item_uuid')
            ->select(['products.name','products.name_ar','products.description','products.description_ar',
            'invoicedetails.id','invoicedetails.item_uuid','invoicedetails.code_type','invoicedetails.item_code','invoicedetails.item','invoicedetails.description',
                'invoicedetails.qty','invoicedetails.net','invoicedetails.discount','invoicedetails.unit_type','invoicedetails.price','invoicedetails.total','invoicedetails.number'
        ])->get();
        return view($view, ['invoicehead' => $invoicehead,'invoicedetails' => $invoicedetails] , $this->returnInvoiceData($invoice_type));
    }

    public function update($uuid ,$request ,$invoice_type = Transaction::POS_RECEIPT) : void {

        $newUuid = Str::uuid();

        DB::beginTransaction();

            DB::table('invoicehead')->where('uuid' , $uuid)->update($this->returnSalesInvoiceHeadData($newUuid ,$invoice_type ,$request));

            if(!empty($request->items)){
                DB::table('invoicedetails')
                    ->where('uuid' ,$uuid)
                    ->delete();
                $this->storeInvoiceItems($request ,$newUuid ,$invoice_type);
            }else{
                // return $request->internal_id;
                DB::table('invoicedetails')->where('invoice_number' , $request->internal_id )->delete();
            }

            DB::commit();
    }
    /**
     * Extra Private
     * Helper Methods
     */


    /**
     * @param $invoice_type
     * @return string
     * @throws \Exception
     */
    private function getInvoiceID($invoice_type = Transaction::POS_RECEIPT) : string{

        return
            DB::table('invoicehead')
                ->where('invoice_type' , $invoice_type)
                ->whereNot('status','Stocktaked')
                ->whereNull('deleted_at')
                    ->select(DB::raw("MAX(CAST(document_uuid AS UNSIGNED)) as id"))
                        ->pluck('id')[0]+1;
    }

    /**
     * @param $invoice_type
     * @return array
     */
    private function returnInvoiceData($invoice_type) : array{
        return [
            'id' => $this->getInvoiceID($invoice_type),
            'products' => DB::table('products')
                ->select('uuid','code_type','item_code','bar_code',
                LaravelLocalization::getCurrentLocale()== 'en' ? 'name as name' : 'name_ar as name',
                LaravelLocalization::getCurrentLocale()== 'en' ? 'description as description' : 'description_ar as description',
                'type_code','type_desc','sell_price as price','currency_code','currency_desc','tax','discount','stock',
                'first_unit_type' ,'second_unit_type' ,'third_unit_type','first_unit_sell_price' ,'second_unit_sell_price', 'third_unit_sell_price')
                ->orderBy('name_ar')
                ->get(),
            'units' => DB::table('units')->select('name_'. LaravelLocalization::getCurrentLocale() . ' as name')->get()
        ];
    }

    /**
     * @param $uuid
     * @param $invoice_type
     * @param $request
     * @param $CustomerRequest
     * @return array
     */
    private function returnSalesInvoiceHeadData($uuid ,$invoice_type ,$request  ,$status = 'Valid') :array{

        $system = DB::table('system_profile')->select('company_name', 'tax_rCode' ,'tax_aCode')->get();
        $branch = DB::table('branches')->where('branch_name' , $request->branch_name)->select('country','governorate','city','building_number','street')->get();


        return [
            'uuid' => $uuid,
            'invoice_type' => $invoice_type ,
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date ?? now(),
            'internal_id' => $request->invoice_number,
            'document_type' => $request->document_type,
            'document_uuid' => $request->invoice_number,
            'document_version' => $request->document_version ?? '1.0',
            'taxpayer_activity_code' =>  $system[0]->tax_aCode,

            'issuer_id' => $system[0]->tax_rCode ?? 1,
            'issuer_type' => 'B',
            'issuer_name' =>  $system[0]->company_name ?? "Exeriya",
            'issuer_country' => $branch[0]->country ?? "EG",
            'issuer_gov' => $branch[0]->governorate ?? "Cairo",
            'issuer_city' => $branch[0]->city ?? "Cairo",
            'issuer_building_number' => $branch[0]->building_number ?? 0,
            'issuer_street' => $branch[0]->street ?? "Cairo",

            'customer_id'=> $request->customer_id ?? 0,
            'customer_type'=> $request->customer_type ?? 'P',
            'customer_name'=> $request->customer_name ?? "عميل نقدي",
            'customer_country'=> $request->customer_country ?? "EG",
            'customer_gov'=> $request->customer_gov  ?? "Cairo",
            'customer_city'=> $request->customer_city ?? "Cairo",
            'customer_building_number'=> $request->customer_building_number ?? "0",
            'customer_street'=> $request->customer_street ?? "Cairo",

            'invoice_discount' => $request->invoice_discount ?? 00.00,
            'invoice_tax' => $request->invoice_tax ?? 00.00,

            // 'total_sales' => $request->totalSales,
            // 'total_net' => $request->totalNet,
            // 'total_items' => $request->transTotalItems,
            // 'total_items_discount' => $request->transTotalItemsDisc,
            // 'total_tax' => $request->transTax,
            // 'total_tax_table' => $request->transTaxTable,
            // 'total_discount' => $request->transDisc,
            // 'discount_after_tax' => $request->transTotalDiscAfterTax ,
            'total' => $request->transTotal,
            'notes' => $request->notes,
            'items_count' => $request->items_count,
            'status' =>  $status ,
            'entry' => Auth::user()->name,
            'is_cash' => 1,
            'branch_name' => DB::table('branches')->pluck('branch_name')[0],
            'created_at' => Carbon::now(),
        ];
    }

    /**
     * @param $request
     * @param $uuid
     * @param $invoice_type
     * @return void
     */
    private function storeInvoiceItems($request,$uuid , $invoice_type ){

        foreach($request->items as  $item){

            DB::table('invoicedetails')->insert([
                'uuid' => $uuid,
                'invoice_number' => $request->invoice_number,
                'invoice_type' => $invoice_type,
                'item_uuid' => $item['uuid'],
                'item' => $item['item'],
                'description' => $item['item'],
                'code_type' => $item['code_type'] ?? "product",
                'item_code' => $item['item_code'],
                'qty' => $item['qty'],
                'unit_type' => $item['unit_type'],
                'price' => $item['unitPrice'],
                'currency' => $item['currency'] ?? "EGP",

                'discount' => $item['disc'],
                'discount_per' => $item['discPer'] ?? 00,

                'net' => $item['net'],
                'total_sales' => $item['qty'] * $item['unitPrice'],
                'total' => $item['total'],
                'number' => $item['number'],
                'created_at' => $request->invoice_date ?? now()
            ]);

            if(isset($item['item_type'])){
                DB::table('products')->where('uuid',$item['uuid'])->update([
                    $item['item_type'] . 'unit_sell_price' => $item['unitPrice']
                ]);
            }

        }
    }


     public function print($uuid , $view){

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
         $company = DB::table('system_profile')->select('company_name','tax_rCode','img')->get();
         $profile = DB::table('branches')->select('mobile','street')->get();

        return view($view, compact('invoicehead','invoicedetails' ,'company','profile'));
     }

}
