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

        $result = null;

        $customQuery = property_exists($query, "class");
        if(!$customQuery) {
            // we will try to instantiate the model
            try {
                $result = $this->{$query->function}($query, $input);        
            }
            catch (\FatalThrowableError $e){
                $customQuery = true;
            }
        }

        if($customQuery) {
            $result = $this->customFunction($query, $input);
            if(!is_array($result)) $result = [$result];
        }

        // if the config requests a model and we have one, we will return the result as a model
        if(property_exists($query, "model") && 
            $result instanceof Illuminate\Database\Eloquent\Collection) {

            foreach($result as $model) {
                $this->addModel($query->model, $model);
            }

            $result = $this->getResponseFromModels($query, $result);

        }
       
        return $result;
    }

    function select($inputQuery, $input) {

        $model = 'App\\' . ucfirst($inputQuery->model);

        $query = $model::query();

        // add where parameters
        $whereParams = $this->getParams($inputQuery, $input);
        $query->where($whereParams);

        $queryResult = null;

        $build = Helper::getKey($inputQuery, "build", new \stdClass());
        foreach($build as $key => $value) {
            // input query comes from the config
            // should be save and there is no other way of doing it
            // whitout punishing the user or having lots of code lines.
            
            eval("\$queryResult = \$query->{\$key}($value);");
        }

        if($queryResult == null) {
            $queryResult = $query->get();
        }
        else if(!is_array($queryResult)) {
            $queryResult = [$queryResult];
        }
        
        return $queryResult;
    }

    function customFunction($query, $input) {
        // extract the values
        $className = 'App\\' . ucfirst($query->class);
        $class = new $className;
        return $class->{$query->function}($query, $input);
    }

    function getModels() {
        return $this->modelsGlobal;
    }

    function create($input) {

        $model = 'App\\' . ucfirst($input->model);

        $query = new $model;

        // add parameters
        $inputs = $input->params;
        foreach($inputs as $key=>$param) {
            $query->$key = $param;
        }

        $query->save();
    }

    function delete($input) {
        $model = 'App\\' . ucfirst($input->model);

        $query = $model::query();

        // add parameters
        $inputs = $input->where;

        $params = $this->transformToQuery($inputs);

        $query->where($params)->delete();
    }

    function update($input) {
        $model = 'App\\' . ucfirst($input->model);

        $query = $model::query();

        // add parameters
        $inputs = $input->params;

        $updateParams = $this->transformToQuery($inputs, true);

        $idObj = new \stdClass();
        $idObj->id = Helper::getKey($inputs, "id", -1);
        $whereParams = $this->transformToQuery(Helper::getKey($input, "where", $idObj));
        $query->where($whereParams)->update($updateParams);
    }

    // Private

    private function addModel($type, $model) {
        Helper::createIfProperty($this->modelsGlobal, $type);
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

    private function buildParamObject($param) {
        $ret = new \stdClass();
        $ret->name = $param;
        return $ret;
    }

    private function transformToQuery($inputs, $separator = false) {
        $params = [];
        foreach($inputs as $key => $param) {
            if(!$separator) $params[] = [$key, $param];
            else $params[$key] = $param;
        }
        return $params;
    }

    private function getParams($inputQuery, $input) {
        $whereParams = [];
        $inputs = Helper::getKey($inputQuery, "inputs", []);
        foreach($inputs as $param) {
            
            if(is_string($param) || is_numeric($param)) {
                $param = $this->buildParamObject($param);
            }
          
            $op = Helper::getKey($param, "op", "=");
            $param_input = $this->getParamInput($param, $input);
            $whereParams[] = [$param->name, $op, $param_input];
        }
        return $whereParams;
    }

    private function getParamInput($param, $input) {
        if(property_exists($param, "value")) return $param->value;
        return $input->{$param->name};
    }
}