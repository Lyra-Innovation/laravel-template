<?php

namespace App;
use App\Helpers\Helper;

class ConfigManager {
    private $config;

    public function __construct() {
        // read the config

        $json = Helper::readFile('config');
        $this->config = $this->importConfig($json);
    }

    public function getConfig() {
        return $this->config;
    }

    // Private

    private function importConfig($input) {
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
