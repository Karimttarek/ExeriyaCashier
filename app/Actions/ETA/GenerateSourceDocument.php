<?php

namespace App\Actions\ETA;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateSourceDocument {

    private $taxable = [];
    private $invoiceLines = [];
    private $taxableItem = [];
    private $totalsTax = array();
    private $totalTaxes = array();

    public $jsonToSub ;
    public function GenerateDocument($uuid){


        $invoiceHead = DB::table('invoicehead')->where('uuid' , $uuid)->select(['id','uuid','internal_id','invoice_number','invoice_date','document_type','document_version',
            'taxpayer_activity_code','issuer_type','issuer_id','issuer_name','issuer_country','issuer_gov','issuer_city','issuer_building_number','issuer_street',
            'customer_type','customer_id','customer_name','customer_country','customer_gov','customer_city','customer_building_number','customer_street','total_tax_table',
            'invoice_discount','invoice_tax','total_sales','total_net','total_items','total_tax','total_discount','total_items_discount','discount_after_tax',
            'total',
        ])->get();
        $invoiceDetails = DB::table('invoicedetails')->where('uuid' , $uuid)->select([
            'id','item_uuid','code_type','item_code','item','description','qty','unit_type','price','currency','tax','tax_per','tax_type','tax_table','tax_table_per',
            'tax_sub_type','taxvalue','taxPervalue','discount','discount_per','total_sales','discount_after_tax','net','total','number'
        ])->get();

        foreach ($invoiceDetails as $item){
            foreach (explode(',',$item->tax_type) as $key => $taxs){
                if(strlen($item->tax_type) > 0){
                    $this->taxable[] = [
                        "taxType"=> explode(',' ,$item->tax_type)[$key],
                        "amount"=> floatval(explode(',' ,$item->taxvalue)[$key]),
                        "subType"=> explode(',' ,$item->tax_sub_type)[$key],
                        "rate"=> floatval(explode(',' ,$item->taxPervalue)[$key])
                    ];
                }else{
                    $this->taxable[] = [
                        "taxType"=> "T1",
                        "amount"=> 0,
                        "subType"=> "V001",
                        "rate"=> 0
                    ];
                }
                if(strlen($item->tax_type) > 0){
                    $this->totalsTax [] = [
                        explode(',' ,$item->tax_type)[$key] => floatval(explode(',' ,$item->taxvalue)[$key])
                    ];
                }else{
                    $this->totalsTax [] = [
                        "T1" => 0
                    ];
                }
            }
            if(!empty($item->tax_table)){
                $this->taxable[] = [
                    "taxType"=> 'T2',
                    "amount"=> floatval($item->tax_table),
                    "subType"=> 'Tbl01',
                    "rate"=> floatval($item->tax_table_per)
                ];
            }
            if(!empty($item->tax_table)){
                $this->totalsTax [] = [
                    'T2' => floatval($item->tax_table)
                ];
            }
            $invoiceLines [] =
                [
                    "description"=> $item->description,
                    "itemType"=> $item->code_type,
                    "itemCode"=> $item->item_code,
                    "unitType"=> $item->unit_type,
                    "quantity"=> $item->qty,
                    "internalCode"=> "",
                    "salesTotal"=> $item->total_sales,
                    "total"=> $item->total,
                    "valueDifference"=> 0.00,
                    "totalTaxableFees"=> 0,
                    "netTotal"=>$item->net,
                    "itemsDiscount"=> $item->discount_after_tax,
                    "unitValue"=> [
                        "currencySold"=> $item->currency,
                        "amountEGP"=> $item->price,
                        "amountSold" => 0,
                        "currencyExchangeRate" => 0
                    ],
                    "discount"=> [
                        "rate"=> $item->discount_per,
                        "amount"=> $item->discount
                    ],
                    "taxableItems" => $this->taxable,
                ];

            $this->taxable = [];
        }

        $sums = [];
        foreach($this->totalsTax as $totalsTax){
            $key = key($totalsTax);
            if(!isset($sums[$key])){
                $sums[$key] = array_sum(array_column($this->totalsTax,$key));
            }
        }
        $this->totalsTax = [];
        foreach($sums as $key => $value){
            $this->totalsTax [] =
                [
                    "taxType"=> $key,
                    "amount"=> round($value ,5)
                ];
        }
        foreach ($invoiceHead as $head)
            $this->jsonToSub = [
                "issuer"=> [
                    "address"=> [
                        "branchID"=> "0",
                        "country"=> $head->issuer_country,
                        "governate"=> $head->issuer_gov,
                        "regionCity"=> $head->issuer_city,
                        "street"=> $head->issuer_street,
                        "buildingNumber"=> $head->issuer_building_number,
                    ],
                    "type"=> $head->issuer_type,
                    "id"=> $head->issuer_id,
                    "name"=> $head->issuer_name
                ],
                "receiver"=> [
                    "address"=> [
                        "country"=> $head->customer_country,
                        "governate"=> $head->customer_gov,
                        "regionCity"=> $head->customer_city,
                        "street"=> $head->customer_street,
                        "buildingNumber"=> $head->customer_building_number,
                    ],
                    "type"=> $head->customer_type,
                    "id"=> !empty($head->customer_id) ? $head->customer_id : "",
                    "name"=> $head->customer_name
                ],
                "documentType"=> $head->document_type,
                "documentTypeVersion"=> $head->document_version ?? '1.0',
                "dateTimeIssued" => \Carbon\Carbon::parse(date('Y-m-d' , strtotime($head->invoice_date)). date('H:i:s' , strtotime(now())) ,'UTC')->format('Y-m-d\TH:i:s\Z'),
//                "dateTimeIssued"=> date("Y-m-d\Th:i:s\Z", strtotime(\Carbon\Carbon::parse($head->invoice_date,'UTC')->addSeconds(30))),
                "taxpayerActivityCode"=> $head->taxpayer_activity_code,
                "internalID"=> $head->internal_id,
                "invoiceLines" => $invoiceLines,
                "totalDiscountAmount"=> $head->total_items_discount,
                "totalSalesAmount"=> $head->total_sales,
                "netAmount"=> $head->total_net,
                "taxTotals"=> $this->totalsTax,
                "totalAmount"=> $head->total,
                "extraDiscountAmount"=> $head->invoice_discount,
                "totalItemsDiscountAmount"=> $head->discount_after_tax
            ];

        return $this->jsonToSub;
    }
}
