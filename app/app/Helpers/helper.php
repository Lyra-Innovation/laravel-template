<?php

namespace App\Helpers;

class Helper
{
    public static function getKey($obj, $key, $default) {
        $return = $default;
        if(property_exists($obj, $key)) $return = $obj->{$key};
        return $return;
    }

    public static function readFile($input) {
        $json = \Storage::get('config/' . $input . ".json");
        $decoded = json_decode($json);
        return $decoded;
    }
}