<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherStoreRequest;
use App\Services\Receipts\ReceiptService;
use Illuminate\Http\Request;


class VoucherController extends Controller
{
    public function __construct(private ReceiptService $receiptService){}

    public function create(){
        return $this->receiptService->create([1,9,11],'receipts.voucher.create');
    }

    public function store(VoucherStoreRequest $request){

        $this->receiptService->store($request);

        return redirect()->route('Voucher.get')->with('status' , __('app.SS'));
    }

    public function edit($uuid){

        return $this->receiptService->edit($uuid , 'receipts.voucher.edit');
    }

    public function update(Request $request , $uuid){

        $this->receiptService->update($request, $uuid);

        return redirect()->route('Voucher.get')->with('status' , __('app.US'));
    }

    public function destroy(Request $request){

        $this->receiptService->destroy($request);

        return redirect()->route('Voucher.get')->with('status' , __('app.DS'));
    }

    public function numberFormat(Request $request){
        return $this->receiptService->numberFormatToText($request->no);
    }

    public function print(VoucherStoreRequest $request){
        // return app('App\Http\Controllers\Transaction\ReceiptsController')->insertAndPrintReceipt($request , 'print.receiptPrint');
    }

    public function printReceipt($uuid){
        return $this->receiptService->print($uuid , 'print.receiptPrint');
    }
}
