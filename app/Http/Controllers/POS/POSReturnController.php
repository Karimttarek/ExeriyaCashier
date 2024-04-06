<?php

namespace App\Http\Controllers\POS;

use App\Enums\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePosRequest;
use App\Http\Requests\UpdatePosRequest;
use App\Services\Invoices\PosReceiptService;

class POSReturnController extends Controller
{
    /**
     *
     */
    public function __construct(private PosReceiptService $PosReceiptService){}


    public function create(){
        return $this->PosReceiptService->create('posreturn.create' , Transaction::POS_RETURN_RECEIPT);
    }

    public function store(StorePosRequest $request){

        $this->PosReceiptService->store($request ,Transaction::POS_RETURN_RECEIPT);
        return redirect()->route('POS.return.create');
    }

    public function edit($uuid){
        return $this->PosReceiptService->edit($uuid ,'posreturn.edit' ,Transaction::POS_RETURN_RECEIPT);
    }

    public function update($uuid ,UpdatePosRequest $request){

        $this->PosReceiptService->update($uuid ,$request ,Transaction::POS_RETURN_RECEIPT);
        return redirect()->route('POS.return.index');
    }

}
