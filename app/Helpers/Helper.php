<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class Helper{

    /**
     *
     *
     * @param  string<string, string>  $input
     */
    public static function uploadPhotoToStorage($photo, $dir , $phName) : string
    {
        if(!empty($photo)){
            if ($photo->isValid()) {
                $ph = $phName.'.'.$photo->extension();
                // $ph->resize(120, 120, function ($constraint) {
                //     $constraint->aspectRatio();
                // });

                $photo->storeAs($dir, $ph);
                return $ph;
            }
        }
        return '';
    }

    /**
     *
     * @return void
     * @param  string<string, string>  $input
     */
    public static function removePhoto($fullUrl) : void
    {
        File::delete($fullUrl);
    }


}
