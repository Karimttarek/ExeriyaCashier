<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpensesRequest;
use App\Http\Requests\ExpensesUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
    public function index(){
        $expenses = DB::table('expenses')->select('id','code','name','statement')->paginate(env('PAGINATE'));
        return view('expenses.get' , compact('expenses'));
    }

    public function create(){
        return view('expenses.create');
    }

    public function store(ExpensesRequest $request){


        DB::table('expenses')->insert([
           'code' => $request->code,
           'name' => $request->name,
           'statement' => $request->statement
        ]);

        return redirect()->route('Expenses.get')->with('status',  __('app.SS'));
    }

    public function edit($id){

        $expense = DB::table('expenses')->where('id',$id)->select('id','code','name','statement')->get();
        return view('expenses.edit' , compact('expense'));
    }

    public function update(ExpensesUpdateRequest $request,$id){
        DB::table('expenses')->where('id',$id)->update([
            'code' => $request->code,
            'name' => $request->name,
            'statement' => $request->statement
        ]);
        return redirect()->route('Expenses.get')->with('status',  __('app.US'));
    }

    public function destroy(Request $request){
        foreach($request->item as $uuids){
            DB::table('expenses')->where('id' , $uuids)->delete();
        }

        return redirect()->route('Expenses.get')->with('status', __('app.DS'));
    }

    public function expFilter(Request $request){
        if($request->ajax() && !empty($request->id)){
            $exp = DB::table('expenses')
                ->where('code' , $request->id)
                ->orWhere('name' , $request->id)
                ->select('code','name','statement')->get();
            return $exp;

        }
    }

}
