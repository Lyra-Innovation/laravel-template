<?php

namespace App\Http\Controllers;

use App\ViewComponent as ViewComponent;
use App\ActionManager as ActionManager;
use App\ConfigManager as ConfigManager;
use Illuminate\Http\Request;

class CoreController extends Controller {

    public function __construct(ViewComponent $view, ActionManager $action, ConfigManager $config) 
    {
        $this->viewManager = $view;
        $this->actionManager = $action;
        $this->configManager = $config;

        //$this->middleware('auth:api');
    }

    public function getView(Request $request) {
      $inputString = $request->getContent();
      $input = json_decode($inputString);
      $this->actionManager->processActions($input->actions);
      return response()->json($this->viewManager->merge($input));
    }

    public function getConfig() {
        return response()->json($this->configManager->getConfig());
    }

}