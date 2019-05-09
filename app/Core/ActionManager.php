<?php

namespace App;
use App\Helpers\Helper;

class ActionManager {
    private $dataManager;

    public function __construct(DataManager $dataManager) {
        $this->dataManager = $dataManager;
    }

    public function processActions($actions) {

        foreach ($actions as $action) {
            
            // at least one time
            $maxNum = 1;
            $i = 0;

            while($i < $maxNum) {
                [$maxNum, $singleAction] = $this->getSingleAction($action, $i);

                // check that can be performed
                // query.class, query.function
                $condition = Helper::getKey($action, "condition", null);
                
                if($condition) {
                    $inputs = Helper::getQueryValues($condition);
                    if(($this->dataManager->customFunction($condition, $inputs))) {
                        $i++;
                        continue;
                    }
                }

                // exec function
                $type = $action->action;
                $this->dataManager->{$type}($singleAction);

                $i++;
            }
            
        }
    }

    private function getSingleAction($action, $i) {
        $num = 1;

        $batch = Helper::getKey($action, "batch", false);
        $newAction = new \stdClass();

        foreach($action as $key => $value) {
            if($batch && Helper::checkIfArray($value)) {
                $newAction->{$key} = $value[$i];
                $newNum = count($value);
                if($num < $newNum) $num = $newNum;
            }
            else $newAction->{$key} = $value;
        }

        return [$num, $newAction];
    }

}
