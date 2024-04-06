<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    private $path = 'image/';
    private $file;

    public function create(){
        $countries = DB::table('countries')->select('code','Desc_en','Desc_ar')->get();
        $governorates = DB::table('governorates')->select('id','Desc_en','Desc_ar')->get();
        $cities = DB::table('cities')->select('governorate_id','Desc_en','Desc_ar')->get();

        return  view('branch.create',compact('countries' ,'governorates' ,'cities'));
    }


    public function store(BranchRequest $request){

        DB::table('branches')->insert([
            'branch_name' => $request->branch_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country' => $request->country,
            'governorate' => $request->governorate,
            'city' => $request->city,
            'building_number' => $request->building_number,
            'street' => $request->street,
            'img' => $this->path.$this->file,
        ]);

        return redirect()->route('Branch.get')->with('status', __('app.US'));
    }

    public function edit($id){
        $countries = DB::table('countries')->select('code','Desc_en','Desc_ar')->get();
        $governorates = DB::table('governorates')->select('id','Desc_en','Desc_ar')->get();
        $cities = DB::table('cities')->select('governorate_id','Desc_en','Desc_ar')->get();
        $branch = DB::table('branches')->where('id' ,$id)->select('id','branch_name', 'email', 'mobile' ,'country' ,'governorate', 'city' ,'building_number','street')->get();
        return  view('branch.edit',compact('branch','countries' ,'governorates' ,'cities'));
    }

    public function update(Request $request , $id){

        DB::table('branches')->where('id' ,$id)->update([
            'branch_name' => $request->branch_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country' => $request->country,
            'governorate' => $request->governorate,
            'city' => $request->city,
            'building_number' => $request->building_number,
            'street' => $request->street,
            'img' => $this->path.$this->file,
        ]);

        return redirect()->route('Branch.get')->with('status', __('app.US'));
    }
}
