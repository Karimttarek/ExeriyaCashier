<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SystemController extends Controller
{

    private $thumbnails_path = 'public/dist/thumbnail/';
    private $thumbnails_public_path = 'storage/dist/thumbnail/';

    public function index(){
        $aCodes = DB::table('activity_types')->select('code','Desc_en','Desc_ar')->get();
        $system = DB::table('system_profile')->select('company_name', 'tax_rCode' ,'tax_aCode' ,'token_certificate')->limit(1)->get();
        return view('system' ,compact('aCodes','system'));
    }
    /**
     * Handle the incoming request.
     */
    public function store(Request $request)
    {

        try{
            DB::beginTransaction();

            $thumbnail = Helper::uploadPhotoToStorage($request->file('thumbnail') , $this->thumbnails_path , 'thumbnail');

            DB::table('system_profile')->truncate();
            DB::table('system_profile')->insert([
                'company_name' => $request->company_name,
                'tax_rCode' => $request->tax_rCode,
                'tax_aCode' => $request->tax_aCode,
                'token_certificate' => $request->token_certificate,
                'img' => !empty($thumbnail) ? $this->thumbnails_public_path.$thumbnail : null ,
            ]);

            if (!empty($request->client_id) && !empty($request->client_secret)){

                DB::table('invoice_portal_auth')->truncate();
                DB::table('invoice_portal_auth')->insert([
                    'client_id' => Crypt::encryptString($request->client_id),
                    'client_secret' => Crypt::encryptString($request->client_secret),
                    'pin_code' => Crypt::encryptString($request->pin),
                ]);
            }

            DB::commit();
        } catch(\Throwable $exception){
            //Helper::removePhoto($this->thumbnails_public_path.$thumbnail);
            DB::rollBack();
            // return redirect()->back()->with('error' , __('app.SWH'));
        }

        return redirect()->back()->with('status' , __('app.SS'));
    }
}
