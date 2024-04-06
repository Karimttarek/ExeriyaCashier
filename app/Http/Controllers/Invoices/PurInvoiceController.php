<?php

namespace App\Http\Controllers\Invoices;

use App\Exports\PurInvoicesExport;
use App\Exports\PurTaxReturnExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceStoreRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Http\Requests\IssuerStoreRequest;
use App\Services\Invoices\InvoicesService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PurInvoiceController extends Controller
{
    private $invoice_type = 1;
    private $receipt_type = 4;
    private $cash_receipt_type = 2;

    public function __construct(private InvoicesService $invoicesService){}

    public function create(){
        return $this->invoicesService->create('transaction.pur.create' ,$this->invoice_type);
    }

    public function store(InvoiceStoreRequest $request , IssuerStoreRequest $Crequest){

        try {
            $this->invoicesService->store($request, $Crequest ,$this->invoice_type ,$this->receipt_type,$this->cash_receipt_type);

            return redirect()->route('Pur.get')->with('status', __('app.SS'));
        } catch (\Throwable $th) {
            return redirect()->route('Pur.create')->with('error', __('app.SWH'));
        }
    }

    public function edit($uuid){

        return $this->invoicesService->edit($uuid , 'transaction.pur.edit',$this->invoice_type);
    }

    public function update(InvoiceUpdateRequest $request , IssuerStoreRequest $Crequest , $uuid){

        try {
            $this->invoicesService->update($request, $Crequest, $uuid  ,$this->invoice_type ,$this->receipt_type ,$this->cash_receipt_type);

            return redirect()->route('Pur.get')->with('status', __('app.US'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('app.SWH'));
        }
    }

    public function getCopy($uuid){
        return $this->invoicesService->edit($uuid , 'transaction.pur.copy',$this->invoice_type);
    }
    /**
     * Extra Methods
     *
     */

    public function taxReturn(Request $request){
        // return Excel::download(new PurTaxReturnExport($request), 'PurchaseInvoicesItem.xlsx');
    }

    public function print($uuid){

        return $this->invoicesService->print($uuid , 'print.purInvoicePrint');
    }

    public function exportToExcel(Request $request){
        // return Excel::download(new PurInvoicesExport($request), 'PurchaseInvoices.xlsx');
    }

    public function search(Request  $request){
        if ($request->ajax()){
            if ($request->number != null){
                $invoices =  app('App\Http\Controllers\Transaction\InvoicesController')->searchInvoice($this->invoice_type,$request->number,'issuer_name');
                return view('render.invoices.PurTbody', compact('invoices' ))->render();
            }else{
                $invoices =  app('App\Http\Controllers\Transaction\InvoicesController')->searchInvoice($this->invoice_type,'');
                return view('render.invoices.PurTbody', compact('invoices' ))->render();
            }
        }
    }

}
