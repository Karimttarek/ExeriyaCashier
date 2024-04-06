<?php

namespace App\Http\Controllers\Invoices;

use App\Exports\SalesInvoicesExport;
use App\Exports\SalesTaxReturnExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceStoreRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Http\Requests\ReceiverStoreRequest;
use App\Services\Invoices\InvoicesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SalesInvoiceController extends Controller
{

    private $invoice_type = 2;
    private $receipt_type = 3;
    private $cash_receipt_type = 1;
    //
    public function __construct(private InvoicesService $invoicesService){}

    public function create(){
        return $this->invoicesService->create('transaction.sales.create' ,$this->invoice_type);
    }

    public function store(InvoiceStoreRequest $request , ReceiverStoreRequest $Crequest){

        try {
            $this->invoicesService->store($request, $Crequest ,$this->invoice_type ,$this->receipt_type,$this->cash_receipt_type);
            return redirect()->route('Sales.get')->with('status', __('app.SS'));
        } catch (\Throwable $th) {
            return redirect()->route('Sales.create')->with('error', __('app.SWH'));
        }
    }

    public function edit($uuid){

        return $this->invoicesService->edit($uuid , 'transaction.sales.edit',$this->invoice_type);
    }
    public function update(InvoiceUpdateRequest $request , ReceiverStoreRequest $Crequest , $uuid){
        try {
            $this->invoicesService->update($request, $Crequest, $uuid  ,$this->invoice_type ,$this->receipt_type ,$this->cash_receipt_type);
            return redirect()->route('Sales.get')->with('status', __('app.US'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('app.SWH'));
        }
    }

    public function getCopy($uuid){
        return $this->invoicesService->edit($uuid , 'transaction.sales.copy',$this->invoice_type);
    }

    /**
     * Extra Methods
     */

    public function saveToDraft(Request $request ,ReceiverStoreRequest $Crequest){
        if($request->ajax()){
            return $this->invoicesService->saveToDraft($request , $Crequest ,$this->invoice_type);
        }
    }
    public function getCities(Request $request){
        if($request->ajax()){
            $cities = DB::table('cities')->where('governorate_id' , $request->id)->select('governorate_id','Desc_en','Desc_ar')->get();
            return view('render.cities' , compact('cities'))->render();

        }
    }

    public function search(Request  $request){
        if ($request->ajax()){
            if ($request->number != null){
                $invoices =  app('App\Http\Controllers\Transaction\InvoicesController')->searchInvoice($this->invoice_type,$request->number ,'customer_name');
                return view('render.invoices.SalesTbody', compact('invoices' ))->render();
            }else{
                $invoices =  app('App\Http\Controllers\Transaction\InvoicesController')->searchInvoice($this->invoice_type,'');
                return view('render.invoices.SalesTbody', compact('invoices' ));
            }
        }
    }

    public function customerFilter(Request $request){
        if($request->ajax() && !empty($request->id)){
            $customer = DB::table('customers')
                ->where('tax_code' , $request->id)
                ->orWhere('name' ,'LIKE' ,'%' . $request->id .'%' )
                ->orWhere('id' , $request->id)
                ->select('id','type','tax_code','name','country','gov','city','building_number','street')->get();
            return $customer;

        }
    }
    public function getTaxesSubTypes(Request $request){
        if($request->ajax()){
            $taxTypes = DB::table('tax_sub_types')->where('TaxTypeReference' , $request->taxType)->select('Code')->get();
        }
        return $taxTypes;
    }

    public function taxReturn(Request $request){

        // return Excel::download(new SalesTaxReturnExport($request), 'SalesInvoicesItem.xlsx');
    }

    public function print($uuid){

        return $this->invoicesService->print($uuid , 'print.salesInvoicePrint');
    }

    public function exportToExcel(Request $request){
        // return Excel::download(new SalesInvoicesExport($request), 'SalesInvoices.xlsx');
    }
}
