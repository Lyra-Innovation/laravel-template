<?php

namespace App;
use App\Helpers\Helper;

class ViewComponent {
    private $obj;
    private $dataManager;

    public function __construct(DataManager $dataManager) {
        $this->dataManager = $dataManager;
    }

    public function merge($input) {
        // read the config

        $json = Helper::readFile('config');
        $config = $this->importConfig($json);

        // check capabilities

        // merge
        $output = new \StdClass();
        foreach($input->views as $view => $viewInput) {
            $output->$view = $this->mergeComponent($config->{$view}->layout, $viewInput);
        }
        return $output;
    }

    private function mergeComponent($config, $input, $num = 0) {

        // root for merging, $input = component
        // we can do checks here, call others...
        $output = new \stdClass();

        $output->type = $config->type;

        [$size, $values] = $this->extractValues($config->values, $input->params, $num);
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
            if($multiple) {
                $newKey = $key . ":" . $newNum;
            }

            [$size, $component] = $this->mergeComponent($nextConfig, $input->children->{$key}, $newNum);
            $output->children->{$newKey} = $component;
            $last = $size;
            $newNum++;
        }
    }

    private function extractValues($values, $input, $num) {
        $output = new \stdClass();

        $size = 1;

        foreach($values as $key => $value) {
            $values = $this->extractSingleValue($value, $input, $key);
            $newSize = count($values);
            $output->{$key} = $num < $newSize ? $values[$num] : $values[0];

            if($newSize > $size) $size = $newSize;
        }

        return [$size, $output];
    }

    private function extractSingleValue($value, $input, $key) {
        // always returns string

        // simple case where the value is already an string
        if(is_string($value)) {
            return [$value];
        }

        $result = null;
        //first we run the query if present
        if(property_exists($value, "query")) {
            $query_params = Helper::getKey($input, $key, new \stdClass());            
            $result = $this->dataManager->exec($value->query, $query_params);
        }

        //if query result is null, we get the default value
        if($result === null) {
            $result = [$value->default];
        }

        return $result;

    }

    function importConfig($input) {
        if (is_array($input) || is_object($input)) {
            foreach ($input as $key => $value) {
                if(is_string($value)) {
                    $arr = explode(' ', trim($value));
                    if ($arr[0] == "import") $input->{$key} = $this->importConfig(Helper::readFile($arr[1]));
                } else if (is_object($value)) {
                    $input->{$key} = $this->importConfig($value);
                } else if (is_array($input->{$key})) {
                    for ($i = 0; $i < count($value); $i++) $input->{$key}[$i] = $this->importConfig($value[$i]);
                }
            }
        }
        return $input;
    }

}
