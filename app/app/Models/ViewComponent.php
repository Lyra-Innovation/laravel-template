<?php

namespace App;

class ViewComponenet {
    private $obj;
    private $dataManager;

    public function __construct(DataManager $dataManager) {
        $this->dataManager = $dataManager;
    }

    public function merge($input) {

        // read the config
        $json = \Storage::get('config/config.json');
        $config = json_decode($json);

        // check capabilities

        return $this->mergeComponent($config->{$input->view}->layout, $input);
    }

    private function mergeComponent($config, $input) {
        // root for merging, $input = component
        // we can do checks here, call others...
        $output = new \stdClass();

        $output->type = $config->type;
        $output->values = $this->extractValues($config->values, $input->params);

        //recursive call foreach children
        $output->children = new \stdClass();
        if(property_exists($config, "children")) {
            foreach($config->children as $key => $nextConfig) {
                $output->children->{$key} = $this->mergeComponent($nextConfig, $input->children->{$key});
            }
        }

        return $output;
    }

    private function extractValues($values, $input) {
        $output = new \stdClass();

        foreach($values as $key => $value) {
            $output->{$key} = $this->extractSingleValue($value, $input, $key);
        }

        return $output;
    }

    private function extractSingleValue($value, $input, $key) {
        // always returns string

        // simple case where the value is already an string
        if(is_string($value)) {
            return $value;
        }

        $result = null;
        //first we run the query if present
        if(property_exists($value, "query")) {
            $query_params = new \stdClass();
            if(property_exists($input, $key)) $query_params = $input->{$key};
            
            $result = $this->dataManager->exec($value->query, $query_params);
        }

        //if query result is null, we get the default value
        if($result == null) {
            $result = $value->default;
        }

        return $result;

    }

}
