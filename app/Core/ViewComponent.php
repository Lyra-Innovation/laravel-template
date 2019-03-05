<?php

namespace App;
use App\Helpers\Helper;

class ViewComponent {
    private $obj;
    private $dataManager;
    private $configManager;

    public function __construct(DataManager $dataManager, ConfigManager $configManager) {
        $this->dataManager = $dataManager;
        $this->configManager = $configManager;
    }

    public function merge($input) {

        $config = $this->configManager->getConfig();

        // merge
        $output = new \stdClass();
        $output->views = new \stdClass();
        foreach($input->views as $key => $viewInput) {
            [$size, $output->views->$key] = $this->mergeComponent($config->{$viewInput->view}->layout, $viewInput->layout);
        }

        // add models
        $output->models = $this->dataManager->getModels();
        return $output;
    }

    private function mergeComponent($config, $input, $num = 0) {

        // root for merging, $input = component
        // we can do checks here, call others...
        $output = new \stdClass();

        $output->type = $config->type;
        $output->events = Helper::getKey($config, "events", new \stdClass());
        $multiple = Helper::getKey($config, "multiple", false);
        $values = Helper::getKey($config, "values", new \stdClass());

        [$size, $values] = $this->extractValues($values, $input->params, $num, $multiple);
        $output->values = $values;

        // recursive call foreach children
        $output->children = new \stdClass();
        if(property_exists($config, "children")) {
            foreach($config->children as $key => $nextConfig) {
                // $output passed by reference since we are going to modify it's attributes
                $this->setComponents($output, $nextConfig, $key, $input);
            }
        }

        return [$size, $output];
    }

    private function setComponents($output, $nextConfig, $key, $input) {

        $multiple = Helper::getKey($nextConfig, "multiple", false);

        $newKey = $key;
        $newNum = 0;
        $last = 1;

        while($newNum < $last) {

            [$size, $component] = $this->mergeComponent($nextConfig, $input->children->{$key}, $newNum);

            if($multiple) {
                $newKey = $key . ":" . $newNum;
            }
            
            if(!$size == 0 || !$multiple) {
                $output->children->{$newKey} = $component;
            }
            
            $last = $size;
            $newNum++;
        }
    }

    private function extractValues($values, $input, $num, $multiple) {
        $output = new \stdClass();

        $size = 0;

        foreach($values as $key => $value) {
            $newSize = 0;
            $isList = Helper::getKey($value, "isList", false);

            [$isStatic, $values] = $this->extractSingleValue($value, $input, $key, $isList);
            if(!$isStatic) {
                $newSize = count($values);
            }

            if($newSize == 0 && !$isStatic) {
                // if it's empty, we can return an empty list or a null value
                $output->{$key} = $isList ? [] : null;
                $size = 0;
            }
            else if($isList) {
                // if we expect a list, we return all the values
                $output->{$key} = $values;
                $size = 1;
            }
            else {
                // if there are more values and it's not a list, we return the $num value
                $output->{$key} = $num < $newSize ? $values[$num] : $values[0];
                if($newSize > $size) $size = $newSize;
            }
            
        }

        return [$size, $output];
    }

    private function extractSingleValue($value, $input, $key, $isList) {
        // always returns string

        // simple case where the value is already an string
        if(is_string($value) || is_bool($value) || is_numeric($value)) {
            return [true , [$value]];
        }

        if(is_array($value)) {
            return [true , [$value]];
        }

        $result = null;
        $isStatic = false;

        //first we run the query if present
        if(property_exists($value, "query")) {
            $query_params = Helper::getKey($input, $key, new \stdClass());            
            $result = $this->dataManager->exec($value->query, $query_params);
        }

        //if query result is null, we get the default value
        if($result == null || ($isList && $result == [])) {
            $default = Helper::getKey($value, "default", null); 
            $result = [$default];
            $isStatic = true;
        }

        return [$isStatic, $result];
    }

}
