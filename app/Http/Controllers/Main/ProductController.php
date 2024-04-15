<?php

namespace App\Http\Controllers\Main;

use App\Actions\ETA\Login;
use App\Actions\ETA\reuseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Imports\ImportProduct;
use App\Models\InvoicesDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use WireUi\Traits\Actions;


class ProductController extends Controller
{
    use Actions;
    private $errorList;
    private $uuid;

    public function create(){
        $id = DB::table('products')->select(DB::raw("MAX(CAST(id AS UNSIGNED)) as id"))->pluck('id')[0]+1;
        $categories = DB::table('categories')->select('id' , 'name')->get();
        $taxTypes = DB::table('tax_types')->select('Code','Desc_en' ,'Desc_ar')->get();
        $unit = DB::table('unit_types')->select('code' ,'desc_en' ,'desc_ar')->get();
        $currency = DB::table('currency')->select('code' ,'Desc_en')->get();
        $types = DB::table('product_types')->select('code','desc_en','desc_ar')->get();
        $units = DB::table('units')->select('name_'.LaravelLocalization::getCurrentLocale() . ' as name')->get();
        return view('product.create' , compact('id','categories','taxTypes','unit','currency' ,'types' ,'units'));
    }

    public function store(ProductStoreRequest $request){

        // return $request;
        DB::beginTransaction();

        $this->uuid = Str::uuid();
        DB::table('products')->insert([
            'uuid' => $this->uuid,
            'code_type' => 'product',
            'bar_code' => $request->bar_code,
            // 'item_code'=> $request->code_type == 'EGS'
            //     ? str_contains($request->item_code ,'EG-') ? $request->item_code : config('eta.registration_number').$request->item_code
            //     : $request->item_code,
            'item_code'=> $request->item_code,
            'parent_code' => $request->parent_code,

            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,

            'purchase_price' => $request->first_unit_pur_price,
            'sell_price' => $request->first_unit_sell_price,

            'type_code' => $request->first_unit_type,
            'type_desc' => $request->first_unit_type,

            'currency_code' => $request->currency,
            'currency_desc' => $request->currency,
            // Units
            'first_unit_type' =>  $request->first_unit_type,
            'first_unit_qty' =>  $request->first_unit_qty,
            'first_unit_pur_price' => $request->first_unit_pur_price,
            'first_unit_sell_price' => $request->first_unit_sell_price,

            'second_unit_type' =>  $request->second_unit_type,
            'second_unit_qty' =>  $request->second_unit_qty,
            'second_unit_pur_price' => $request->second_unit_pur_price,
            'second_unit_sell_price' => $request->second_unit_sell_price,

            'third_unit_type' =>  $request->third_unit_type,
            'third_unit_qty' =>  $request->third_unit_qty,
            'third_unit_pur_price' => $request->third_unit_pur_price,
            'third_unit_sell_price' => $request->third_unit_sell_price,

            'active_from' => $request->active_from ?? \Carbon\Carbon::now('Africa/Cairo'),
            'active_to' => $request->active_to ?? \Carbon\Carbon::now('Africa/Cairo'),
            //
            'request_reason' => $request->request_reason,
            'ported' => 0,
            'entry' => Auth::user()->name,
            'created_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo'))),
        ]);

        DB::table('manufacturs')->insert([
            'parent_uuid' => $this->uuid,
            'parent_name' => $request->name,
            'child_uuid' => $this->uuid,
            'child_name' => $request->name,
            'price' => $request->first_unit_pur_price,
            'qty' => 1,
            'number' => 0,
            'created_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo'))),
        ]);
        DB::commit();

        return redirect()->route('Product.get')->with('status', __('app.SS') );

    }

    public function edit($uuid){

        $data = DB::table('products')->where('uuid' , $uuid)->select(
            'uuid',
            'code_type',
            'bar_code',
            'parent_code',
            'codeUsageRequestId',
            'item_code',
            'name',
            'name_ar',
            'description',
            'description_ar',
            'type_code',
            'type_desc',
            'category_id',
            'category_name',
            'item_type',
            'product_types.desc_en',
            'product_types.desc_ar',
            'purchase_price',
            'sell_price',
            'currency_code',
            'currency_desc',
            'tax',
            'tax_code',
            'discount',
            'stock',


            'first_unit_type',
            'first_unit_qty',
            'first_unit_pur_price' ,
            'first_unit_sell_price',

            'second_unit_type',
            'second_unit_qty',
            'second_unit_pur_price' ,
            'second_unit_sell_price',

            'third_unit_type',
            'third_unit_qty',
            'third_unit_pur_price' ,
            'third_unit_sell_price',

            'active_from',
            'active_to',
            'active',
            'request_reason'
        )->leftJoin('product_types' ,'product_types.code' ,'=' ,'products.item_type')->get();

        $unit = DB::table('unit_types')->select('code' ,'desc_en' ,'desc_ar')->get();
        $units = DB::table('units')->select('name_'.LaravelLocalization::getCurrentLocale() . ' as name')->get();
        $currency = DB::table('currency')->select('code' ,'Desc_en')->get();
        return view('product.edit' , compact('data', 'unit' ,'currency','units'));
    }

    public function update(ProductUpdateRequest $request , $uuid){

        DB::table('products')->where('uuid' , $uuid)->update([
            'code_type' => 'product',
            'bar_code' => $request->bar_code,
            // 'item_code'=> $request->code_type == 'EGS'
            //     ? str_contains($request->item_code ,'EG-') ? $request->item_code : config('eta.registration_number').$request->item_code
            //     : $request->item_code,
            'item_code'=> $request->item_code,
            'parent_code' => $request->parent_code,

            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,

            'purchase_price' => $request->first_unit_pur_price,
            'sell_price' => $request->first_unit_sell_price,

            'type_code' => $request->first_unit_type,
            'type_desc' => $request->first_unit_type,

            'currency_code' => $request->currency,
            'currency_desc' => $request->currency,
            // Units
            'first_unit_type' =>  $request->first_unit_type,
            'first_unit_qty' =>  $request->first_unit_qty,
            'first_unit_pur_price' => $request->first_unit_pur_price,
            'first_unit_sell_price' => $request->first_unit_sell_price,

            'second_unit_type' =>  $request->second_unit_type,
            'second_unit_qty' =>  $request->second_unit_qty,
            'second_unit_pur_price' => $request->second_unit_pur_price,
            'second_unit_sell_price' => $request->second_unit_sell_price,

            'third_unit_type' =>  $request->third_unit_type,
            'third_unit_qty' =>  $request->third_unit_qty,
            'third_unit_pur_price' => $request->third_unit_pur_price,
            'third_unit_sell_price' => $request->third_unit_sell_price,

            'active_from' => $request->active_from ?? \Carbon\Carbon::now('Africa/Cairo'),
            'active_to' => $request->active_to ?? \Carbon\Carbon::now('Africa/Cairo'),
            //
            'request_reason' => $request->request_reason,

            'updated_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo'))),
        ]);

        DB::table('manufacturs')->where('uuid' , $uuid)->update([
            'parent_name' => $request->name,
            'updated_at' => date("Y-m-d\TH:i", strtotime(\Carbon\Carbon::now('Africa/Cairo'))),
        ]);

        if(isset($request->updateEinvoice)){
            $access_token = app('App\Http\Controllers\Auth\InvoicePortalController')->index();

            $po = DB::table('products')->where('uuid' , $uuid)->select('ported' ,'codeUsageRequestId')->get();
            if($po[0]->ported === 1 && !empty($po[0]->codeUsageRequestId) ){
                $lang = LaravelLocalization::getCurrentLocale();
                $response = Http::withHeaders([
                    'Authorization' => $access_token,
                    'Content-Type' => 'application/json',
                    'Accept-Language' => $lang
                    ])->put(config('eta.PRDapiBaseUrl').'api/v1.0/codetypes/requests/codes/'.$po[0]->codeUsageRequestId,[
                        'itemCode' => config('eta.regregistration_number').$request->item_code,
                        'codeName' => $request->name,
                        'codeNameAr' => $request->name_ar,
                        "description" => $request->desc,
                        "descriptionAr" => $request->desc_ar,
                        "activeTo" => date('Y/m/d' , strtotime(explode(',' , $request->active_to)[0])),
                        "activeFrom" => date('Y/m/d' , strtotime(explode(',' , $request->active_from)[0])),
                        "parentCode" => $request->parent_code,
                        "requestReason" => $request->request_reason,
                    ]);

                    if(!empty($response['errors']['id'][0])){
                        return redirect()->route('Product.get')->with('error', $response['errors']['id'][0]);
                    }if(!empty($response['error']['details'])){

                        for ($i=0; $i < count($response['error']['details']); $i++){
                        $this->errorList .=$response['error']['details'][$i]['target'] . ':'. $response['error']['details'][$i]['message'] ;
                        }
//                        return $this->errorList;
                        // return $response['error']['details'][0]['target'] . ':'. $response['error']['details'][0]['message'];
                        return redirect()->route('Product.get')->with('error', $this->errorList);
                        // return redirect()->route('Product.get')->with('error','Validation error');
                    }else{
                            return redirect()->route('Product.get')->with('status', __('app.US'));
                        }
            }
        }

        return redirect()->route('Product.get')->with('status', __('app.US'));
    }

    public function destroy(Request $request)
    {
        foreach($request->item as $uuids){
            if (!InvoicesDetails::where('item_uuid' ,$uuids)->exists()){
                DB::beginTransaction();

                DB::table('products')->where('uuid' , $uuids)->delete();
                DB::table('manufacturs')->where('parent_uuid' , $uuids)->delete();

                DB::commit();

                DB::rollBack();
            }
        }

        return redirect()->back()->with('status', __('app.DS'));
    }

    public function importProductToExcel(Request $request){

            try {
                Excel::import( new ImportProduct , $request->file('file'));
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                return $e;
            }
    }

    public function Getitems(Request $request){
        if(LaravelLocalization::getCurrentLocale() == 'en'){
            $name = 'name';
        }else{
            $name = 'name_ar';
        }
        $data= DB::table('products')
                ->where('name','LIKE' ,'%'.$request->name.'%')
                ->orWhere('name_ar' ,'LIKE' ,'%'.$request->name.'%')
                ->orWhere('item_code' ,'LIKE' ,'%'.$request->name.'%')
                ->orWhere('bar_code' ,'LIKE' ,'%'.$request->name.'%')
                ->select($name .' as name')->get();
        return $data;
    }

    public function Purfilter(Request $request){
        $product = DB::table('products')
            ->where('name',$request->name)
            ->orWhere('name_ar',$request->name)
            ->select('uuid','code_type','item_code',
                LaravelLocalization::getCurrentLocale()== 'en' ? 'name as name' : 'name_ar as name',
                LaravelLocalization::getCurrentLocale()== 'en' ? 'description as description' : 'description_ar as description',
                'type_code','type_desc','first_unit_pur_price as price','currency_code','currency_desc','tax','discount','stock',
                'first_unit_type' ,'second_unit_type' ,'third_unit_type')->get();

        return [
            'product' => $product,
        ];

    }

    public function Salesfilter(Request $request){

        $product = DB::table('products')
            ->where('name',$request->name)
            ->orWhere('name_ar',$request->name)
            ->select('uuid','code_type','item_code',
                LaravelLocalization::getCurrentLocale()== 'en' ? 'name as name' : 'name_ar as name',
                LaravelLocalization::getCurrentLocale()== 'en' ? 'description as description' : 'description_ar as description',
                'type_code','type_desc','sell_price as price','currency_code','currency_desc','tax','discount','stock',
                'first_unit_type' ,'second_unit_type' ,'third_unit_type')->get();


        $stock = DB::table('invoicedetails')
                        ->whereNotIn('invoicehead.status', ['Invalid','Canceled'])
                        ->select(
                            DB::raw('
                            IFNULL(
                                SUM(CASE WHEN invoicedetails.invoice_type IN(1,4,6) THEN
                                CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                                WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty ELSE invoicedetails.qty END
                                ELSE -
                                CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                                WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
                                ELSE invoicedetails.qty * manufacturs.qty END END)
                            ,0)
                             as balance
                            '),

                        )
                        ->leftJoin('invoicehead' , 'invoicehead.uuid' ,'=','invoicedetails.uuid')
                        ->leftJoin('products' , 'invoicedetails.item_uuid' ,'=','products.uuid')
                        ->rightJoin('manufacturs' , 'manufacturs.parent_uuid' , '=' , 'invoicedetails.item_uuid')
                        ->where('item',$request->name)
                        ->orderBy('invoicehead.invoice_date')->get();
        $data = [
            'product' => $product,
            'stock' => $stock[0]->balance
        ];
        return $data;


    }

    public function ItemStock(Request $request){

        $stock = DB::table('invoicedetails')
                        ->whereNotIn('invoicehead.status', ['Invalid','Canceled'])
                        ->select(
                            DB::raw('
                            IFNULL(
                                SUM(CASE WHEN invoicedetails.invoice_type IN(1,4,6) THEN
                                CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                                WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty ELSE invoicedetails.qty END
                                ELSE -
                                CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                                WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
                                ELSE invoicedetails.qty * manufacturs.qty END END)
                            ,0)
                             as balance
                            '),

                        )
                        ->leftJoin('invoicehead' , 'invoicehead.uuid' ,'=','invoicedetails.uuid')
                        ->leftJoin('products' , 'invoicedetails.item_uuid' ,'=','products.uuid')
                        ->rightJoin('manufacturs' , 'manufacturs.parent_uuid' , '=' , 'invoicedetails.item_uuid')
                        ->where('products.name',$request->name)
                        ->orWhere('products.name_ar',$request->name)
                        ->orWhere('products.bar_code',$request->name)
                        ->orWhere('products.item_code',$request->name)
                        ->orderBy('invoicehead.invoice_date')->get();

            return $stock[0]->balance;
    }

    public function uploadToInvoice($uuid){

        $access_token = ( new Login )->execute();
        $item = DB::table('products')->where('uuid' ,$uuid)->select(
            'code_type as codeType',
            'parent_Code as parentCode',
            'item_code as itemCode',
            'name as codeName',
            'name_ar as codeNameAr',
            'active_from as activeFrom',
            'active_to as activeTo',
            'description as description',
            'description_ar as descriptionAr',
            'request_reason as requestReason',
            )->get();

            $lang = LaravelLocalization::getCurrentLocale();
            $response = Http::withHeaders([
                'Authorization' => $access_token,
                'Content-Type' => 'application/json',
                'Accept-Language' => $lang
                ])->post(config('eta.PRDapiBaseUrl').'api/v1.0/codetypes/requests/codes',[
                    'items' => $item
                ]);
                if(!empty($response['failedItems'])){
                    foreach($response['failedItems'] as $index){
                        $error = $index['errors'][0];
                        if($error == "No need to create code usage for code '{0}' because this taxpayer already can use it"){
                            DB::table('products')->where('uuid' ,$uuid)->update([ 'ported' => 1 , 'active' => 'Valid' ]);
                            $error = __('app.IAEYCUI');
                        }
                    }
                    return redirect()->route('Product.get')->with('status', $error);
                }
                elseif(!empty($response['passedItems'])){
                    foreach($response['passedItems'] as $code)
                    DB::table('products')->where('uuid' ,$uuid)->update([ 'ported' => 1 , 'active' => 'Valid' , 'codeUsageRequestId' => $code['codeUsageRequestId'] ]);
                    return redirect()->route('Product.get')->with('status', __('app.PUS'));
                }else{
                    return redirect()->route('Product.get')->with('error', __('app.SWH'));
                }
                return redirect()->route('Product.get')->with('error', __('app.SWH'));

    }

    public function reuseCode($uuid){

        $access_token = ( new Login )->execute();
        $item = DB::table('products')->where('uuid' ,$uuid)->select(
            'code_type as codetype',
            'item_code as itemCode',
        )->get();

        $lang = LaravelLocalization::getCurrentLocale();
        $response = Http::withHeaders([
            'Authorization' => $access_token,
            'Content-Type' => 'application/json',
            'Accept-Language' => $lang
        ])->put(config('eta.PRDapiBaseUrl').'api/v1.0/codetypes/requests/codeusages',[
            'items' => $item
        ]);

        if(!empty($response['failedItems'])){
            foreach($response['failedItems'] as $index){
                $error = $index['errors'][0];
                if(str_contains($error ,'There is a pending code usage  request for this item code')){
                    DB::table('products')->where('uuid' ,$uuid)->update([ 'ported' => 1 , 'active' => 'Valid' ]);
                    $error = __('app.There is a pending code usage  request for this item code');
                }elseif (str_contains($error ,'No need to create code usage for code')){
                    DB::table('products')->where('uuid' ,$uuid)->update([ 'ported' => 1 , 'active' => 'Valid' ]);
                    $error = __('app.IAEYCUI');
                }elseif (str_contains($error , 'find code')){
                    $error = __('app.Could not find this code to be reused');
                }
            }
            return redirect()->route('Product.get')->with('status', $error);
        }
        elseif(!empty($response['passedItems'])){
            DB::table('products')->where('uuid' ,$uuid)->update([ 'ported' => 1 , 'active' => 'Valid' ]);
            return redirect()->route('Product.get')->with('status', __('app.PUS'));
        }
        return redirect()->route('Product.get')->with('error', __('app.SWH'));
    }

    public function reuseCodes(Request $request){

        return (new reuseCode())->execute(explode(',' ,$request->uuids));
    }

    /**
     *
     */
    public function changeTypePur(Request $request){

        $data= DB::table('products')
            ->where('name',$request->name)
            ->orWhere('name_ar',$request->name)
            ->select($request->type.'unit_pur_price')->get();
        return $data;
    }

    public function changeTypeSales(Request $request){

        $data= DB::table('products')
            ->where('name',$request->name)
            ->orWhere('name_ar',$request->name)
            ->select($request->type.'unit_sell_price')->get();
        return $data;
    }

}
