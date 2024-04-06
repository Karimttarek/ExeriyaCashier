<?php

namespace App\Actions\ETA;

class Serialize{
    public static function hashSerializedString($documentStructure)
    {
        $serializedData = self::serialize($documentStructure);
        return hash('sha256', $serializedData);
    }

    public static function serialize($documentStructure)
    {
        if(!is_array($documentStructure))
        {

            return '"'.$documentStructure.'"';
        }

        $serializedString = "";

        foreach($documentStructure as $item => $value)
        {

            if(!is_array($value))
            {
                $serializedString .= strtoupper('"'.$item.'"');
                $serializedString .= self::serialize($value);
            }

            if(is_array($value))
            {
                $serializedString .= strtoupper('"'.$item.'"');
                foreach($value as $subItem => $subValue)
                {
                    $serializedString .= is_int($subItem)?strtoupper('"'.$item.'"'):strtoupper('"'.$subItem.'"');
                    $serializedString .= self::serialize($subValue);
                }
            }
        }

        return $serializedString;
    }
}
