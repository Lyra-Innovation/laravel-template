<?php

namespace App\Helpers;

class Helper
{
    public static function getKey($obj, $key, $default = null) {
        $return = $default;
        if(property_exists($obj, $key)) $return = $obj->{$key};
        return $return;
    }

    public static function readFile($input) {
        $json = \Storage::get('config/' . $input . ".json");
        $decoded = json_decode($json);
        return $decoded;
    }

    public static function createIfProperty($obj, $key) {
        if(!property_exists($obj, $key)) {
            $obj->{$key} = new \stdClass();
        }
    }

    public static function checkIfCollection($obj) {
        if($obj instanceof \Illuminate\Database\Eloquent\Collection) return true;
        if($obj instanceof \Illuminate\Support\Collection) return true;

        return false;
    }

    public static function checkIfArray($obj) {
        if(is_array($obj)) return true;
        if(Helper::checkIfCollection($obj)) return true;

        return false;
    }

    public static function getQueryValues($query) {
        $output = new \stdClass();
        foreach($query->inputs as $input) {
            if(property_exists($input, "value")) {
                $output->{$input->name} = $input->value;
            }
        }
        return $output;
    }


}