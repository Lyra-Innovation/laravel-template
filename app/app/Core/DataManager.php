<?php

namespace App;
use App\Helpers\Helper;

class DataManager {
    private $queryCache;
    private $modelsGlobal;

    function __construct() {
        $this->queryCache = new \stdClass();
        $this->modelsGlobal = new \stdClass();
    }

    function exec($query, $input) {

        $result = "";

         // we will try to instantiate the model
        try {
            $result = $this->{$query->function}($query, $input);        
        }
        catch (\FatalThrowableError $e){
            $result = $this->customFunction();
        }

        // if the config requests a model, we will return the result as a model
        if($query->model) {

            foreach($result as $model) {
                $this->addModel($query->model, $model);
            }
            
            $result = $this->getResponseFromModels($query, $result);

        }
       
        return $result;
    }

    function select($inputQuery, $input) {

        $model = 'App\\' . ucfirst($inputQuery->model);

        // add where parameters
        $whereParams = [];
        foreach($inputQuery->inputs as $param) {
            $op = Helper::getKey($input, "op", "=");
            $whereParams[] = [$param, $op, $input->{$param}];
        }

        $query = $model::where($whereParams);
        
        $queryResult = $query->get();
        return $queryResult;
    }

    function customFunction() {
        // extract the values
        /*$attributeArray = $queryResult->map(function ($item) use ($inputQuery) {
            return $item[$inputQuery->attribute];
        });*/
    }

    function getModels() {
        return $this->modelsGlobal;
    }

    // Private

    private function addModel($type, $model) {

        Helper::createIfProperty($this->modelsGlobal, $type);
        //Helper::createIfProperty($modelsGlobal->{$type}, $model->id);
        $this->modelsGlobal->{$type}->{$model->id} = $model;
    }

    private function getResponseFromModels($query, $models) {
        $response = [];
        foreach($models as $model) {
            $responseModel = new \stdClass();
            $responseModel->model = $query->model;
            $responseModel->id = $model->id;
            $responseModel->attribute = $query->attribute;
            $response[] = $responseModel;
        }
        return $response;
    }

}
