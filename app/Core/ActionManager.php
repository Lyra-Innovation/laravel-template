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
            $type = $action->action;
            $this->dataManager->{$type}($action);
        }
    }

}
