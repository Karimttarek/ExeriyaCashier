<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

Trait UploadImages
{

    public function uploadImage($img , $path){

        $file_extention  = $img->getClientOriginalExtension();
        $file_name = $img->hashName(); // .'.' . $file_extention
        $img->move($path , $file_name); 

        return $file_name;
    }

    
    public function deleteImage ($uuid, $table , $column ,$path){

        $img = DB::table($table)->where('uuid' , $uuid)->pluck($column);
        $image = '/home/fullmark/public_html/admin/'. $path .$img[0];
        
        //return $image;
        if(File::exists($image)) {
            File::delete($image);
        }
        else{
            return 'Image not exist';
        }
    }
}
