<?php

namespace App\Http\Controllers;

use App\ViewComponenet as ViewComponenet;

class CoreController extends Controller {

    public function __construct(ViewComponenet $view)
    {
        $this->view = $view;
    }

    public function getView($id) {
        
    }

    public function getConfig() {
        return $this->view->merge("test");
    }

}