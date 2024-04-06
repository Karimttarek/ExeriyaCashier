<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceStoreRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Http\Requests\IssuerStoreRequest;
use App\Services\Invoices\InvoicesService;

class PurReturnController extends Controller
{

    private $invoice_type = 3;
    private $receipt_type = 6;
    private $cash_receipt_type = 9;

    public function __construct(private InvoicesService $invoicesService){}

    public function create(){
        return $this->invoicesService->create('transactionReturn.pur.create' ,$this->invoice_type);
    }


    public function store(InvoiceStoreRequest $request , IssuerStoreRequest $Crequest){

        try {
            $this->invoicesService->store($request, $Crequest ,$this->invoice_type ,$this->receipt_type,$this->cash_receipt_type);

            return redirect()->route('PurReturn.get')->with('status', __('app.SS'));
        } catch (\Throwable $th) {
            return redirect()->route('PurReturn.create')->with('error', __('app.SWH'));
        }

    }

    public function edit($uuid){

        return $this->invoicesService->edit($uuid , 'transactionReturn.pur.edit',$this->invoice_type);
    }

    public function update(InvoiceUpdateRequest $request , IssuerStoreRequest $Crequest , $uuid){

        try {
            $this->invoicesService->update($request, $Crequest, $uuid  ,$this->invoice_type ,$this->receipt_type ,$this->cash_receipt_type);

            return redirect()->route('PurReturn.get')->with('status', __('app.US'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('app.SWH'));
        }
    }

    public function getCopy($uuid){
        return $this->invoicesService->edit($uuid , 'transactionReturn.pur.copy',$this->invoice_type);
    }
    public function print($uuid){

        return $this->invoicesService->print($uuid , 'print.purInvoicePrint');
    }
}
