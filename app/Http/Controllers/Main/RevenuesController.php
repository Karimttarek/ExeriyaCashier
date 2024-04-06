<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\RevenuesStoreRequest;
use App\Http\Requests\RevenuesUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenuesController extends Controller
{
    public function create(){
        return view('revenues.create');
    }

    public function store(RevenuesStoreRequest $request){


        DB::table('revenues')->insert([
            'code' => $request->code,
            'name' => $request->name,
            'statement' => $request->statement
        ]);

        return redirect()->route('Revenues.get')->with('status',  __('app.SS'));
    }

    public function edit($id){

        $revenues = DB::table('revenues')->where('id',$id)->select('id','code','name','statement')->get();
        return view('revenues.edit' , compact('revenues'));
    }

    public function update(RevenuesUpdateRequest $request,$id){

        DB::table('revenues')->where('id',$id)->update([
            'code' => $request->code,
            'name' => $request->name,
            'statement' => $request->statement
        ]);
        return redirect()->route('Revenues.get')->with('status',  __('app.US'));
    }

    public function destroy(Request $request){
        foreach($request->item as $uuids){
            DB::table('revenues')->where('id' , $uuids)->delete();
        }

        return redirect()->route('Revenues.get')->with('status', __('app.DS'));
    }

    public function revFilter(Request $request){
        if($request->ajax() && !empty($request->id)){
            $rev = DB::table('revenues')
                ->where('code' , $request->id)
                ->orWhere('name' , $request->id)
                ->select('code','name','statement')->get();
            return $rev;

        }
    }
}
