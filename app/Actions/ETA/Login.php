<?php

namespace App\Actions\ETA;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class Login{

    public function execute(){

        $auth = DB::table('invoice_portal_auth')->select('client_id','client_secret','pin_code')->get();
        $response = Http::asForm()->post(config('eta.PRDidSrvBaseUrl').'connect/token', [
            'grant_type' => 'client_credentials',
            'client_id' =>Crypt::decryptString($auth[0]->client_id),
            'client_secret' =>Crypt::decryptString($auth[0]->client_secret) ,
        ]);

        if(isset($response['access_token'])){
            return 'Bearer ' .$response['access_token'];
        }else{
            abort( 500 );
            return response()->json(['error' => 'Connection error.']);
        }
    }
}
