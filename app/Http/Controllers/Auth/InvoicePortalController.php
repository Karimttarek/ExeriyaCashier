<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class InvoicePortalController extends Controller
{
    //
    public function index(){
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
            }

    }

    public function login(Request $request){

        try {
            $auth = DB::table('invoice_portal_auth')->select('client_id','client_secret','pin_code')->get();
            if(!empty($auth[0]) && Crypt::decryptString($auth[0]->pin_code) == $request->pin_code){
                $response = Http::asForm()->post(config('eta.PRDidSrvBaseUrl').'connect/token', [
                    'grant_type' => 'client_credentials',
                    'client_id' =>Crypt::decryptString($auth[0]->client_id),
                    'client_secret' =>Crypt::decryptString($auth[0]->client_secret) ,
                ]);

                // return $response;
                session()->put('access_token','Bearer '.$response['access_token']);
                return redirect()->back()->with('status', 'Login Success to invoice portal');
            }else{
                return redirect()->back()->with('error', 'Wrong pin code');
            }

        } catch (DecryptException $e) {
            return  $e;
        }
    }

    public function logout(){

        session()->put('access_token','');
        return redirect()->back()->with('status', 'You have logged out from invoice portal');
    }
}
