<?php

namespace App;

class ViewComponenet {
    private $obj;

    public function merge($input) {

        // read the config
        $json = \Storage::get('config/config.json');
        $config = json_decode($json);

        return $this->mergeComponent($config, $input);
    }

    private function mergeComponent($config, $input) {
        // root for merging, $input = component
        // we can do checks here, call others...

        $output = new \stdClass();

        $output->type = $config->type;
        $output->values = $this->extractValues($config->values, $input->values);

        //recursive call foreach children
        $output->children = new \stdClass();
        foreach($config->children as $key => $nextConfig) {
            $output->children[$key] = mergeComponent($nextConfig, $input[$key]);
        }

        return $output;
    }

    private function extractValues($values, $input) {
        $output = new \stdClass();

        foreach($values as $key => $value) {
            $output[$key] = $this->extractSingleValue($value, $input[$key]);
        }

        return $output;
    }

    private function extractSingleValue($value, $input) {
        // always returns string

        // simple case where the value is already an string
        if(is_string($value)) {
            return $value;
        }

        $result = null;
        //first we run the query if present
        if(property_exists("query", $value)) {
            // $result = $this->queryManager->exec($value->query);
        }

        //if query result is null, we get the default value
        if($result == null) {
            $result = $value->default;
        }

        return $result;

    }

}
