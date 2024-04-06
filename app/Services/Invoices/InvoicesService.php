<?php

namespace App\Services\Invoices;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoicesService{

    /**
     * @param $view
     * @param $invoice_type
     * @throws \Exception
     */
    public function create($view ,$invoice_type ){
        return view($view ,$this->returnInvoiceData($invoice_type) );
    }


    public function store($request , $CustomerRequest , $invoice_type,$receipt_type ,$cash_receipt_type) : void{

        $uuid = Str::uuid();

        DB::beginTransaction();

            in_array($invoice_type , array(1,3))
               ? DB::table('invoicehead')->insert($this->returnPurInvoiceHeadData($uuid ,$invoice_type ,$request ,$CustomerRequest))

               : DB::table('invoicehead')->insert($this->returnSalesInvoiceHeadData($uuid ,$invoice_type ,$request ,$CustomerRequest));


            if(!empty($request->items)) {
                $this->storeInvoiceItemsAndUpdatePrices($request, $uuid, $invoice_type);
            }
            $this->storeCustomer($CustomerRequest);

            $this->storeReceipt($uuid, null ,$request , $receipt_type);

            if($request->is_cash == 1){
                $this->storeReceipt(Str::uuid(), $uuid ,$request , $cash_receipt_type);
            }
        DB::commit();
    }

    public function edit($uuid ,$view,$invoice_type){

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
        return view($view, ['invoicehead' => $invoicehead,'invoicedetails' => $invoicedetails] , $this->returnInvoiceData($invoice_type));
    }

    public function update($request , $CustomerRequest, $uuid , $invoice_type,$receipt_type ,$cash_receipt_type) : void {

        $newUuid = Str::uuid();

        DB::beginTransaction();

           in_array($invoice_type , array(1,3))
               ? DB::table('invoicehead')->where('uuid' , $uuid)->update($this->returnPurInvoiceHeadData($newUuid ,$invoice_type ,$request ,$CustomerRequest))

               : DB::table('invoicehead')->where('uuid' , $uuid)->update($this->returnSalesInvoiceHeadData($newUuid ,$invoice_type ,$request ,$CustomerRequest));

            if(!empty($request->items)){
                DB::table('invoicedetails')
                    ->where('uuid' ,$uuid)
                    ->delete();
                $this->storeInvoiceItemsAndUpdatePrices($request ,$newUuid ,$invoice_type);
            }else{
                // return $request->internal_id;
                DB::table('invoicedetails')->where('invoice_number' , $request->internal_id )->delete();
            }
            $this->storeCustomer($CustomerRequest);

            DB::table('receipts')->where('uuid' , $uuid)->delete();
            $this->storeReceipt($newUuid , null ,$request , $receipt_type);

            DB::table('receipts')->where('reference' , $uuid)->delete();
            if($request->is_cash == 1){
                $this->storeReceipt(Str::uuid() , $newUuid ,$request , $cash_receipt_type);
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
    private function getInvoiceID($invoice_type) : string{

        return $invoice_type == 2
            ?
            Carbon::now()->format('Ymd'). '-' . DB::table('invoicehead')
                    ->where('invoice_type' , $invoice_type)
                    ->whereDate('invoice_date' , '>=' , new Carbon('first day of this month'))
                    ->whereDate('invoice_date' ,'<=' ,new Carbon('last day of this month'))->count() +1
            : Carbon::now()->format('Ymd').random_int(100000, 999999);

    }

    /**
     * @param $invoice_type
     * @return array
     */
    private function returnInvoiceData($invoice_type) : array{
        return [
            'id' => $this->getInvoiceID($invoice_type),
            'products' => DB::table('products')->select('*')->orderBy('name_ar')->get(),
            'taxTypes' => DB::table('tax_types')->select('Code','Desc_en' ,'Desc_ar')->get(),
            'countries' => DB::table('countries')->select('code','Desc_en','Desc_ar')->get(),
            'governorates' => DB::table('governorates')->select('id','Desc_en','Desc_ar')->get(),
            'cities' => DB::table('cities')->select('governorate_id','Desc_en','Desc_ar')->get(),
            'profile' => DB::table('system_profile')->select('company_name','tax_rCode','tax_aCode')->get(),
            'customers' => DB::table('customers')->select('tax_code','name')->get(),
            'unit' => DB::table('unit_types')->select('code' ,'desc_en' ,'desc_ar')->get(),
            'currency' => DB::table('currency')->select('code' ,'Desc_en')->get(),
            'branches' => DB::table('branches')->select('branch_name','country','governorate','city','building_number','street')->get()
        ];
    }
    /**
     * @param $uuid
     * @param $invoice_type
     * @param $request
     * @param $CustomerRequest
     * @return array
     */
    private function returnPurInvoiceHeadData($uuid ,$invoice_type ,$request ,$CustomerRequest ,$status = 'Valid') : array{

        $system = DB::table('system_profile')->select('company_name', 'tax_rCode' ,'tax_aCode')->get();
        $branch = DB::table('branches')->where('branch_name' , $request->branch_name)->select('country','governorate','city','building_number','street')->get();

        return [
            'uuid' => $uuid,
            'invoice_type' => $invoice_type ,
            'invoice_number' => $request->internal_id,
            'invoice_date' => date("Y-m-d h:i", strtotime($request->invoice_date)),
            'internal_id' => $request->internal_id,
            'document_type' => $request->document_type,
            'document_version' => $request->document_version ?? '1.0',
            'taxpayer_activity_code' => $system[0]->tax_aCode,

            'issuer_id' => $CustomerRequest->customer_id,
            'issuer_type' => $CustomerRequest->customer_type,
            'issuer_name' => $CustomerRequest->customer_name,
            'issuer_country' => $CustomerRequest->customer_country,
            'issuer_gov' => $CustomerRequest->customer_gov,
            'issuer_city' => $CustomerRequest->customer_city,
            'issuer_building_number' => $request->customer_building_number,
            'issuer_street' => $CustomerRequest->customer_street,

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
            'status' => $status,
            'entry' => Auth::user()->name,
            'is_cash' => $request->is_cash,
            'branch_name' => $request->branch_name,
            'created_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo'))),
            'updated_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')))
        ];
    }

    /**
     * @param $uuid
     * @param $invoice_type
     * @param $request
     * @param $CustomerRequest
     * @return array
     */
    private function returnSalesInvoiceHeadData($uuid ,$invoice_type ,$request ,$CustomerRequest ,$status = 'Pending') :array{

        $system = DB::table('system_profile')->select('company_name', 'tax_rCode' ,'tax_aCode')->get();
        $branch = DB::table('branches')->where('branch_name' , $request->branch_name)->select('country','governorate','city','building_number','street')->get();


        return [
            'uuid' => $uuid,
            'invoice_type' => $invoice_type ,
            'invoice_number' => $request->internal_id,
            'invoice_date' => date("Y-m-d H:i", strtotime($request->invoice_date)),
            'internal_id' => $request->internal_id,
            'document_type' => $request->document_type,
            'document_version' => $request->document_version ?? '1.0',
            'taxpayer_activity_code' =>  $system[0]->tax_aCode,

            'issuer_id' => $system[0]->tax_rCode,
            'issuer_type' => 'B',
            'issuer_name' =>  $system[0]->company_name,
            'issuer_country' => $branch[0]->country,
            'issuer_gov' => $branch[0]->governorate,
            'issuer_city' => $branch[0]->city,
            'issuer_building_number' => $branch[0]->building_number,
            'issuer_street' => $branch[0]->street,

            'customer_id'=> $CustomerRequest->customer_id,
            'customer_type'=> $CustomerRequest->customer_type,
            'customer_name'=> $CustomerRequest->customer_name,
            'customer_country'=> $CustomerRequest->customer_country,
            'customer_gov'=> $CustomerRequest->customer_gov,
            'customer_city'=> $CustomerRequest->customer_city,
            'customer_building_number'=> $CustomerRequest->customer_building_number,
            'customer_street'=> $CustomerRequest->customer_street,

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
//            'status' =>  $status ,
            'entry' => Auth::user()->name,
            'is_cash' => $request->is_cash,
            'branch_name' => $request->branch_name,
            'created_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo'))),
            'updated_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')))
        ];
    }

    private function returnInvoiceTaxableItems($uuid ,$request ,$invoice_type){
        foreach($request->items as  $item) {
//            $this->updateItemPrice($invoice_type ,$item['uuid'] ,$item['unitPrice']);
            if(!empty($item['taxable'])) {
                for ($i = 0; $i < count($item['taxable']) - 1; $i++) {
                    return [
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
                        'tax_type' => implode(',', $item['taxable'][$i]['tax_type']),
                        'tax_sub_type' => implode(',', $item['taxable'][$i]['tax_sub_type']),
                        'taxvalue' => implode(',', $item['taxable'][$i]['taxvalue']),
                        'taxPervalue' => implode(',', $item['taxable'][$i]['taxPervalue']),

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
                    ];
                }
            }
        }
    }

    private function returnInvoiceNonTaxableItems($uuid ,$request ,$invoice_type){
        foreach($request->items as  $item){
//            $this->updateItemPrice($invoice_type ,$item['uuid'] ,$item['unitPrice']);
            if(empty($item['taxable'])) {
                return [
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
                ];
            }
        }
    }
    /**
     * @param $request
     * @param $uuid
     * @param $invoice_type
     * @return void
     */
    private function storeInvoiceItemsAndUpdatePrices($request,$uuid , $invoice_type){

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
            $this->updateItemPrice($invoice_type ,$item['uuid'] ,$item);
        }
    }

    /**
     * @param $uuid
     * @param $reference
     * @param $request
     * @param $receipt_type
     * @return void
     */
    private function storeReceipt($uuid, $reference , $request , $receipt_type){
        DB::table('receipts')->insert([
            'uuid' =>  $uuid,
            'reference' => $reference,
            'no' => DB::table('receipts')->whereIn('receipt_type' ,[$receipt_type])->select(DB::raw("MAX(CAST(no AS UNSIGNED)) as id"))->pluck('id')[0]+1,
            'receipt_type' => $receipt_type,
            'receipt_date' => $request->invoice_date,
            'statement' => $request->internal_id,
            'receiver_uuid' => $request->customer_id,
            'receiver_name' => $request->customer_name,
            'value' => $request->transTotal,
            'value_text' => (new \NumberFormatter( 'ar', \NumberFormatter::SPELLOUT))->format($request->transTotal)
        ]);
    }
     private function storeCustomer($CustomerRequest) : void{

        if (!Customer::where('name' , $CustomerRequest->customer_name)->exists()){
            DB::table('customers')->insert([
                'uuid' => Str::uuid(),
                'type' => $CustomerRequest->customer_type,
                'name'=> $CustomerRequest->customer_name,
                'tax_code'=> $CustomerRequest->customer_id,
                'country'=> $CustomerRequest->customer_country,
                'gov'=> $CustomerRequest->customer_gov,
                'city'=> $CustomerRequest->customer_city,
                'building_number'=> $CustomerRequest->customer_building_number,
                'street'=> $CustomerRequest->customer_street,
            ]);
        }
     }

     private function updateItemPrice($invoice_type, $itemUUID , $item){
        if(isset($item['item_type'])){

            if($invoice_type == 1){
                DB::table('products')->where('uuid',$itemUUID)->update([
                   $item['item_type'] . 'unit_pur_price' => $item['unitPrice']
                ]);
            }elseif ($invoice_type == 2 ){
                DB::table('products')->where('uuid',$itemUUID)->update([
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


     /**
      * Ajax Form
      */

    public function saveToDraft($request , $CustomerRequest , $invoice_type ) : string{

        $uuid = Str::uuid();
        DB::beginTransaction();

        DB::table('invoicehead')->where('invoice_number' , $request->internal_id)->delete();
        DB::table('invoicedetails')->where('invoice_number' , $request->internal_id)->delete();
        in_array($invoice_type , array(1,3))
            ? DB::table('invoicehead')->insert($this->returnPurInvoiceHeadData($uuid ,$invoice_type ,$request ,$CustomerRequest ,'Draft'))

            : DB::table('invoicehead')->insert($this->returnSalesInvoiceHeadData($uuid ,$invoice_type ,$request ,$CustomerRequest,'Draft'));


        if(!empty($request->items)) {
            $this->storeInvoiceItemsAndUpdatePrices($request ,$uuid ,$invoice_type);
        }
        DB::commit();

        return "Saved";
    }
}
