<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Traits\UploadImages;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SystemProfileController extends Controller
{

    private $path = 'image/';
    private $file;
    use UploadImages;

    public function index(){
        $profile = DB::table('system_profile')->select(
            'company_name',
            'tax_rCode',
            'tax_aCode',
            'owner',
            'email',
            'mobile',
            'country',
            'governorate',
            'city',
            'building_number',
            'street',
            'img',
        )->get();
        $countries = DB::table('countries')->select('code','Desc_en','Desc_ar')->get();
        $governorates = DB::table('governorates')->select('id','Desc_en','Desc_ar')->get();
        $cities = DB::table('cities')->select('governorate_id','Desc_en','Desc_ar')->get();
        $aCodes = DB::table('activity_types')->select('code','Desc_en','Desc_ar')->get();

        // return $profile;
        return  view('systemProfile',compact('profile','countries' ,'governorates' ,'cities','aCodes'));
    }

    public function store(Request $request){


        try {
            $request->validate([
                'pin' => 'nullable|min:6',
                'pin_confirmation' => 'nullable|min:6|same:pin',
            ]);

            DB::beginTransaction();

            DB::table('system_profile')->truncate();

            DB::table('system_profile')->Insert([
                'company_name' => $request->company_name,
                'tax_rCode' => $request->tax_rCode,
                'tax_aCode' => $request->tax_aCode,
                'owner' => $request->owner,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country' => $request->country,
                'governorate' => $request->governorate,
                'city' => $request->city,
                'building_number' => $request->building_number,
                'street' => $request->street,
                'img' => $this->path.$this->file,
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
        }catch (\Throwable $exception){
//            DB::rollBack();
        }

        return redirect()->route('SystemProfile.get')->with('status' ,__('app.SS'));
    }

//    public function update(Request $request){
//
//        $validate = $request->validate([
//            'pin' => 'nullable|min:6',
//            'pin_confirmation' => 'nullable|min:6|same:pin',
//        ]);
//
//        // DB::beginTransaction();
//
//        DB::table('system_profile')->update([
//            'company_name' => $request->company_name,
//            'tax_rCode' => $request->tax_rCode,
//            'tax_aCode' => $request->tax_aCode,
//            'owner' => $request->owner,
//            'email' => $request->email,
//            'mobile' => $request->mobile,
//            'country' => $request->country,
//            'governorate' => $request->governorate,
//            'city' => $request->city,
//            'building_number' => $request->building_number,
//            'street' => $request->street,
//            'img' => $this->path.$this->file,
//        ]);
//        DB::table('invoice_portal_auth')->truncate();
//
//        if (!empty($request->client_id) && !empty($request->client_secret)){
//            DB::table('invoice_portal_auth')->insert([
//                'client_id' => Crypt::encryptString($request->client_id),
//                'client_secret' => Crypt::encryptString($request->client_secret),
//                'pin_code' => Crypt::encryptString($request->pin),
//            ]);
//        }
//        config('app.tax_registration' , 'EG-'.$request->tax_rCode . '-');
//
//        // DB::commit();
//
//        return redirect()->route('SystemProfile.get')->with('status' ,__('app.US'));
//    }
}
