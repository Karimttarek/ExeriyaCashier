<?php

namespace App\Actions\ETA;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class reuseCode{

    private $status;
    /**
     * @param array $uuid
     * @return string
     */
    public function execute($uuids) : string{

        foreach ($uuids as $uuid){
            $items = DB::table('products')
                ->where('uuid' ,$uuid)
                ->where('code_type' ,'EGS')
//                ->whereNot('item_code' ,  'like', '%' . config('eta.regregistration_number') .'%' )
                ->select('code_type as codetype','item_code as itemCode')->get();
        }
        if (!empty($items)){
            $lang = LaravelLocalization::getCurrentLocale();
            $response = Http::withHeaders([
                'Authorization' => ( new Login )->execute(),
                'Content-Type' => 'application/json',
                'Accept-Language' => $lang
            ])->put(config('eta.PRDapiBaseUrl').'api/v1.0/codetypes/requests/codeusages',[
                'items' => $items
            ]);
        }

        if(!empty($response['failedItems'])){
            foreach($response['failedItems'] as $error){
                foreach ($error['errors'] as $index){
                    if(str_contains($index ,'There is a pending code usage  request for this item code')){
                        DB::table('products')->where('uuid' ,$uuid)->update([ 'ported' => 1 , 'active' => 'Valid' ]);
                        $this->status .= __('app.There is a pending code usage  request for this item code');
                    }elseif (str_contains($index ,'No need to create code usage for code')){
                        DB::table('products')->where('uuid' ,$uuid)->update([ 'ported' => 1 , 'active' => 'Valid' ]);
                        $this->status  .= __('app.IAEYCUI');
                    }elseif (str_contains($index , 'find code')){
                        $this->status .= __('app.Could not find this code to be reused');
                    }
                }
            }
        }
        elseif(!empty($response['passedItems'])){
            $this->status = __('app.PUS');
        }else{
            $this->status = __('app.SWH') ;
        }
        return $this->status;

    }

}
