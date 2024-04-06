<?php

namespace App\Traits;

Trait ApiResponse {


    public function getCurrentLang(){
        return app()->getlocal();
    }

    public function returnData($data = [] , $msg){
        return response()->json([
            'status' =>'true',
            'message' => $msg,
            'data' => $data ,
            
        ]);
    }

    public function returnError($error){
        return response()->json([
            'status' =>'false',
            'message' => $error
        ]);
    }

    public function returnSuccess($msg){
        return response()->json([
            'status' =>'true',
            'message' => $msg
        ]);
    }

}