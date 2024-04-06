<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index(){
        $clients = DB::table('customers')->select('uuid','id','type','name','tax_code','email','mobile','street')->paginate(env('PAGINATE'));
        return view('client.get' , compact('clients'));
    }

    public function create(){
        $countries = DB::table('countries')->select('code','Desc_en','Desc_ar')->get();
        $governorates = DB::table('governorates')->select('id','Desc_en','Desc_ar')->get();
        $cities = DB::table('cities')->select('governorate_id','Desc_en','Desc_ar')->get();
        return view('client.create',compact('countries' ,'governorates','cities'));
    }
    public function store(CustomerStoreRequest $request){

        $this->validate($request,['name' => ['required','unique:customers,name']]);
        DB::table('customers')->insert([
            'uuid' => Str::uuid(),
            'type'=>$request->type,
            'tax_code' => $request->tax_code,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->phone,
            'country'=>$request->country,
            'gov' => $request->gov,
            'city'=> $request->city,
            'building_number' => $request->building_number,
            'street' => $request->street,
            'created_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')))
        ]);

        return redirect()->route('Client.get')->with('status', __('app.SS'));
    }

    public function edit($uuid){
        $countries = DB::table('countries')->select('code','Desc_en','Desc_ar')->get();
        $governorates = DB::table('governorates')->select('id','Desc_en','Desc_ar')->get();
        $cities = DB::table('cities')->select('governorate_id','Desc_en','Desc_ar')->get();
        $client = DB::table('customers')->where('uuid' , $uuid)
            ->select('uuid','type','tax_code','name','email','mobile','country','gov','city','building_number','street')->get();
        return view('client.edit',compact('client','countries' ,'governorates','cities'));
    }
    public function update(CustomerUpdateRequest $request,$uuid){

        DB::table('customers')->where('uuid' ,$uuid)->update([
            'type'=>$request->type,
            'tax_code' => $request->tax_code,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->phone,
            'country'=>$request->country,
            'gov' => $request->gov,
            'city'=> $request->city,
            'building_number' => $request->building_number,
            'street' => $request->street,
            'updated_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')))
        ]);

        return redirect()->route('Client.get')->with('status', __('app.US'));
    }

    public function destroy(Request  $request){
        foreach($request->item as $uuids){
            DB::table('customers')->where('uuid' , $uuids)->delete();
        }

        return redirect()->back()->with('status', __('app.DS'));
    }
}
