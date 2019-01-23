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

        $inputString = '{
          "view": "playerprofile",
          "params": {
            "name_lang": {
              "id": 2
            },
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