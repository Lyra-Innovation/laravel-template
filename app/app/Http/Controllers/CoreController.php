<?php

namespace App\Http\Controllers;

use App\ViewComponent as ViewComponent;

class CoreController extends Controller {

    public function __construct(ViewComponent $view)
    {
        $this->view = $view;
    }

    public function getView($id) {
        
    }

    public function getConfig() {

        $inputString = '{
          "view": "playerprofile",
          "params": {
            "name_value": {
              "id": 2
            }
          },
          "children": {
            "profile-data": {
              "params": {},
              "children": {
                "0": {
                  "params" :{
                    "id": 2
                  },
                  "children" : {}
                }
              }
            }
          }
        }
        ';

        $input = json_decode($inputString);

        return json_encode($this->view->merge($input));
    }

}