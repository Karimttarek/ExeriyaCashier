<?php

namespace App\Actions\ETA;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CancelInvoice {

    public function execute($uuid){
        $document_uuid = implode('' ,$uuid);

        $lang = LaravelLocalization::getCurrentLocale();
        $access_token = ( new Login )->execute();
        $response = Http::withHeaders([
            'Authorization' => $access_token,
            'Content-Type' => 'application/json',
            'Accept-Language' => $lang
        ])->put(env('PRDapiBaseUrl').'api/v1/documents/state/'.$document_uuid.'/state' ,[
            'status' => 'cancelled',
            'reason' => "Wrong invoice details"
        ]);
        if (isset($response['error'])){
            $errorMessage = $response['error']['details'][0]['message'];

            if($errorMessage == 'Provided Status is invalid, based on the current document status.' || $errorMessage == 'الحالة المقدمة غير صالحة ، بناءً على حالة المستند الحالية.'){
                DB::table('invoicehead')->where('document_uuid', $uuid)->update([
                   'status' => 'Invalid'
                ]);
                DB::table('receipts')->where('uuid' ,$uuid)->where('reference',$uuid)->delete();
                return redirect()->route('Sales.get')->with('error', __('app.ALREADYINVALID'));
            }elseif($errorMessage = "Document status can't be changed after limit time exceeded"){
                return redirect()->route('Sales.get')->with('error',"Document status cannot be changed after limit time exceeded");
            }
            else{
                return redirect()->route('Sales.get')->with('error',$errorMessage);
            }
        }
        if($response == 'true'){
            DB::table('invoicehead')->where('document_uuid', $uuid)->update([
                'status' => 'Canceled'
             ]);
            DB::table('receipts')->where('uuid' ,$uuid)->where('reference',$uuid)->delete();
             return redirect()->route('Sales.get')->with('error', __('app.CANCELED'));
        }
        else{
            return redirect()->route('Sales.get')->with('error', __('app.SWH'));
        }
    }
}
