<?php

namespace App\Actions\ETA;

use App\Jobs\CheckInvoiceStatusJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ResponseError
{

    /**
     * @param $submitionUUID
     * @return string
     */
    private function CheckInvoiceStatus($submitionUUID) : string{
        $access_token = app('App\Http\Controllers\Auth\InvoicePortalController')->index();
        $response = Http::withHeaders([
            'Authorization' => $access_token,
            'Content-Type' => 'application/json',
        ])->get(config('eta.PRDapiBaseUrl').'api/v1.0/documentSubmissions/'.$submitionUUID);

        return $response['overallStatus'];
    }

    public function handle($response , $request){
        if(isset($response['submissionId'])){

            DB::table('invoicehead')->where('uuid' , $request->uuid)->update([
                'submission_uuid' => $response['submissionId'],
                'status' => 'Valid',
                'document_uuid' => $response['acceptedDocuments'][0]['uuid']
            ]);

//            $status = $this->CheckInvoiceStatus($response['submissionId']);
//            if($status !== "inProgress"){
//                DB::table('invoicehead')->where('uuid' , $request->uuid)->update([
//                    'status' => $status,
//                ]);
//            }
            return redirect()->route('Sales.get')->with('status', __('app.AI'));
        }

        if(isset($response['rejectedDocuments'])){
            $id = $response['rejectedDocuments'][0]['error']['target'];
            $errorString = '';
            foreach($response['rejectedDocuments'][0]['error']['details'] as $error ){
                $errorString .= 'Invoice '.$id .' : ';
                $errorString .= $error['propertyPath'] .' # '. $error['target'] . ":". $error['message'];
                return redirect()->route('Sales.get')->with('error', $errorString);
            }
        }if(isset($response['error'])){
            return redirect()->route('Sales.get')->with('error', $response['error']);
        }else{
            return redirect()->route('Sales.get')->with('error',__('app.SWH'));
        }
    }
}
