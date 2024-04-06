<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceStoreRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Http\Requests\ReceiverStoreRequest;
use App\Services\Invoices\InvoicesService;

class SalesReturnController extends Controller
{

    private $invoice_type = 4;
    private $receipt_type = 7;
    private $cash_receipt_type = 8;

    public function __construct(private InvoicesService $invoicesService){}

    public function create(){
        return $this->invoicesService->create('transactionReturn.sales.create' ,$this->invoice_type);
    }

    public function store(InvoiceStoreRequest $request , ReceiverStoreRequest $Crequest){

        try {
            $this->invoicesService->store($request, $Crequest ,$this->invoice_type ,$this->receipt_type,$this->cash_receipt_type);

            return redirect()->route('SalesReturn.get')->with('status', __('app.SS'));
        } catch (\Throwable $th) {
            return redirect()->route('SalesReturn.create')->with('error', __('app.SWH'));
        }

    }

    public function edit($uuid){

        return $this->invoicesService->edit($uuid , 'transactionReturn.sales.edit',$this->invoice_type);
    }

    public function update(InvoiceUpdateRequest $request , ReceiverStoreRequest $Crequest , $uuid){

        try {
            $this->invoicesService->update($request, $Crequest, $uuid  ,$this->invoice_type ,$this->receipt_type ,$this->cash_receipt_type);
            return redirect()->route('SalesReturn.get')->with('status', __('app.US'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('app.SWH'));
        }
    }

    public function getCopy($uuid){
        return $this->invoicesService->edit($uuid , 'transactionReturn.sales.copy',$this->invoice_type);
    }
    public function print($uuid){

        return $this->invoicesService->print($uuid , 'print.salesInvoicePrint');
    }

}
