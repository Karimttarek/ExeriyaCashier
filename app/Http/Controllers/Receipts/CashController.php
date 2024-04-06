<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashStoreRequest;
use App\Services\Receipts\ReceiptService;
use Illuminate\Http\Request;

class CashController extends Controller
{
    public function __construct(private ReceiptService $receiptService){}

    public function create(){
        return $this->receiptService->create([2,5 ,8,10],'receipts.cash.create');
    }

    public function store(CashStoreRequest $request){

        $this->receiptService->store($request);

        return redirect()->route('Cash.get')->with('status' , __('app.SS'));
    }

    public function edit($uuid){

        return $this->receiptService->edit($uuid , 'receipts.cash.edit');
    }

    public function update(Request $request , $uuid){

        $this->receiptService->update($request, $uuid);

        return redirect()->route('Cash.get')->with('status' , __('app.US'));
    }

    public function destroy(Request $request){

        $this->receiptService->destroy($request);

        return redirect()->route('Cash.get')->with('status' , __('app.DS'));
    }

    public function numberFormatToText(Request $request){
        return $this->receiptService->numberFormatToText($request->no);
    }

    public function print(CashStoreRequest $request){
        // return app('App\Http\Controllers\Transaction\ReceiptsController')->insertAndPrintReceipt($request , 'print.receiptCashPrint');
    }

    public function printReceipt($uuid){
        return $this->receiptService->print($uuid , 'print.receiptCashPrint');
    }
}
