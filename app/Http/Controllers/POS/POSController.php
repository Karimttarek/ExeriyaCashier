<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePosRequest;
use App\Http\Requests\UpdatePosRequest;
use App\Services\Invoices\PosReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{

    /**
     *
     */
    public function __construct(private PosReceiptService $PosReceiptService){}


    public function create(){
        return $this->PosReceiptService->create();
    }

    public function store(StorePosRequest $request){
        $this->PosReceiptService->store($request);
        return redirect()->route('POS.create');
    }

    public function edit($uuid){
        return $this->PosReceiptService->edit($uuid ,'pos.edit');
    }

    public function update($uuid ,UpdatePosRequest $request){

        $this->PosReceiptService->update($uuid ,$request);
        return redirect()->route('POS.index');
    }

    /**
     * Extra Methods
     */
    public function changeType(Request $request){

        $data= DB::table('products')
            ->where('name',$request->name)
            ->orWhere('name_ar',$request->name)
            ->select($request->type.'unit_sell_price')->get();
        return $data;
    }

}
