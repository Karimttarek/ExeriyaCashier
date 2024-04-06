<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManufacturStoreRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ManufacturController extends Controller
{

    public function create(){
        $products = DB::table('products')->select('id','uuid',
            LaravelLocalization::getCurrentLocale() == 'en' ? 'products.name as name' : 'products.name_ar as name'
        )->get();
        return view('manufactur.create' , compact('products'));
    }

    public function store(ManufacturStoreRequest $request){
        try {
            DB::beginTransaction();

            if(!empty($request->items)){
                foreach($request->items as  $item){
                    DB::table('manufacturs')->insert([
                        'parent_uuid' => explode('*',$request->parent)[0],
                        'parent_name' => explode('*',$request->parent)[1],
                        'number' => $item['number'],
                        'child_uuid' => $item['uuid'],
                        'child_name' => $item['item'],
                        'price' => $item['price'],
                        'qty' => $item['qty'],
                        'created_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')))
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('Manufactur.get')->with('status', __('app.SS'));
        }catch (\InvalidArgumentException $exception){
            throw new $exception("Invalid Argument Exception");
            DB::rollBack();
        }

    }

    public function edit($parent_uuid){
        $products = DB::table('products')->select('id','uuid',
            LaravelLocalization::getCurrentLocale() == 'en' ? 'products.name as name' : 'products.name_ar as name'
        )->get();
        $parent_product = DB::table('products')->where('products.uuid' ,$parent_uuid)
            ->select('products.id','products.uuid',
            LaravelLocalization::getCurrentLocale() == 'en' ? 'products.name as name' : 'products.name_ar  as name')
            ->get();

        $relateds = DB::table('manufacturs')->where('parent_uuid' , $parent_uuid)
            ->leftJoin('products' ,'products.uuid', '=' ,'manufacturs.child_uuid' )
            ->select('manufacturs.id' ,'manufacturs.parent_uuid','manufacturs.child_uuid',
                LaravelLocalization::getCurrentLocale() =='en' ? 'products.name as name' : 'products.name_ar as name',
                LaravelLocalization::getCurrentLocale() =='en' ? 'products.description as description' : 'products.description_ar as description',
                'manufacturs.qty','manufacturs.number','manufacturs.price')
            ->get();

        return view('manufactur.edit', compact('products' ,'parent_product' ,'relateds'));
    }

    public function update(ManufacturStoreRequest $request ,$parent_uuid){
        try {
            DB::beginTransaction();

            DB::table('manufacturs')->where('parent_uuid' ,$parent_uuid)->delete();

            if(!empty($request->items)){
                foreach($request->items as  $item){
                    DB::table('manufacturs')->insert([
                        'parent_uuid' => explode('*',$request->parent)[0],
                        'parent_name' => explode('*',$request->parent)[1],
                        'number' => $item['number'],
                        'child_uuid' => $item['uuid'],
                        'child_name' => $item['item'],
                        'price' => $item['price'],
                        'qty' => $item['qty'],
                        'created_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo'))),
                        'updated_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo')))
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('Manufactur.get')->with('status', __('app.US'));
        }catch (\InvalidArgumentException $exception){
            throw new $exception("Invalid Argument Exception");
            DB::rollBack();
        }
    }

    public function destroy(Request $request){
        foreach($request->item as $ids){
            DB::table('manufacturs')->where('parent_uuid' , $ids)->delete();
        }
        return redirect()->route('Manufactur.get')->with('status', __('app.DS'));
    }

    public function Getitems(Request $request){
        if(LaravelLocalization::getCurrentLocale() == 'en'){
            $name = 'name';
        }else{
            $name = 'name_ar';
        }
        $data= DB::table('products')
            ->where(['item_type' => 1])
            ->where('name','LIKE' ,'%'.$request->name.'%')
            ->orWhere('name_ar' ,'LIKE' ,'%'.$request->name.'%')
            ->orWhere('item_code' ,'LIKE' ,'%'.$request->name.'%')
            ->select('uuid','id',$name .' as name')->get();
        return $data;
    }

    public function itemsFilter(Request $request){

        $data= DB::table('products')
            ->where('name',$request->name)
            ->orWhere('name_ar',$request->name)
            ->select('uuid','code_type','item_code',
                LaravelLocalization::getCurrentLocale()== 'en' ? 'name as name' : 'name_ar as name',
                LaravelLocalization::getCurrentLocale()== 'en' ? 'description as description' : 'description_ar as description',
                'purchase_price')->get();
        return $data;

    }
}
