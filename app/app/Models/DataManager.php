<?php

namespace App;

class DataManager {
    private $executedQueries;

    function exec($query, $input) {

        $result = "";

         // we will try to instantiate the model
        try {
            $result = $this->{$query->function}($query, $input);
            
        }
        catch (\FatalThrowableError $e){
            $result = $this->customFunction();
        }
       
        return $result;
    }

    function select($inputQuery, $input) {

        $model = 'App\\' . ucfirst($inputQuery->model);

        // add where parameters
        $whereParams = [];
        foreach($inputQuery->inputs as $param) {
            $op = '=';
            if(property_exists($input, "op")) $op = $input->op;

            $whereParams[] = [$param, $op, $input->{$param}];
        }

        $query = $model::where($whereParams);
        
        $queryResult = $query->get();

        // extract the values
        $attributeArray = $queryResult->map(function ($item) use ($inputQuery) {
            return $item[$inputQuery->attribute];
        });

        return $attributeArray;
    }

    function customFunction() {
        
    }
}
