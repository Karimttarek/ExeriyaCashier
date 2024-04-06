<?php

namespace App\Http\Controllers\eInvoice;

use App\Actions\ETA\GenerateFullDocument;
use App\Actions\ETA\GenerateSourceDocument;
use App\Actions\ETA\Login;
use App\Actions\ETA\ResponseError;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SalesInvoicePortalController extends Controller
{
//    private $taxable = [];
//    private $invoiceLines = [];
//    private $taxableItem = [];
//    private $totalsTax = array();
//    private $totalTaxes = array();

    protected $generateSourceDocument;
    protected $generateFullDocument;
    public function __construct(GenerateSourceDocument $GenerateSourceDocument , GenerateFullDocument $GenerateFullDocument){
        $this->generateSourceDocument = $GenerateSourceDocument;
        $this->generateFullDocument = $GenerateFullDocument;
    }

    public function submitDoc($uuid){
       $sourceJsonDocument =  $this->generateSourceDocument->GenerateDocument($uuid);

        $mainDir = '\_signer\\';
        // PUT DOCUMENT JSON IN SOURCE FILE
        $signerFolder = base_path($mainDir);
        $fileName = $signerFolder.'SourceDocumentJson.json';
        file_put_contents($fileName, json_encode($sourceJsonDocument , JSON_UNESCAPED_UNICODE));

        // RUN BATCH SCRIPT
        $batchFile = base_path($mainDir).'SubmitInvoices';
        exec($batchFile,$Result);
        if (file_exists(base_path($mainDir).'FullSignedDocument.json')){
            $fullDoc = file_get_contents(base_path($mainDir).'FullSignedDocument.json');
            $signature =  file_get_contents(base_path($mainDir).'Cades.txt');

            unlink(base_path($mainDir).'FullSignedDocument.json');
            unlink(base_path($mainDir).'SourceDocumentJson.json');
            unlink(base_path($mainDir).'Cades.txt');
            unlink(base_path($mainDir).'CanonicalString.txt');

            if(strlen($signature) > 40) {
                $access_token = (new Login)->execute();

                $response = Http::withHeaders([
                    'Authorization' => $access_token,
                ])->withBody($fullDoc, 'application/json')->post(config('eta.PRDapiBaseUrl') . 'api/v1/documentsubmissions');

//                return ( new ResponseError)->handle($response , $request);
            }

        }
    }

    public function Serialize(Request $request){
        $token_cert = DB::table('system_profile')->pluck('token_certificate')[0];
        $sourceJsonDocument =  $this->generateSourceDocument->GenerateDocument($request->id);
        return [
            'document' => json_encode($sourceJsonDocument , JSON_UNESCAPED_UNICODE),
            'token_cert' => $token_cert,
        ];
//        return json_encode($sourceJsonDocument , JSON_UNESCAPED_UNICODE);
    }
    public function uploadToInvoice(Request $request){

        if($request->fullDocument == 'No slots found' || $request->fullDocument == 'Certificate not found' || $request->fullDocument == 'no device detected'){

            return redirect()->route('Sales.get')->with('error' , $request->fullDocument);
        }else{

        $access_token = ( new Login )->execute();
        $response = Http::withHeaders([
            'Authorization' => $access_token,
        ])->withBody($request->fullDocument,'application/json')->post(config('eta.PRDapiBaseUrl').'api/v1/documentsubmissions');

        return ( new ResponseError)->handle($response , $request);

        }
    }

}
