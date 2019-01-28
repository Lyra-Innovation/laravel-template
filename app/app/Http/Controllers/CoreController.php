<?php

namespace App\Http\Controllers;

use App\ViewComponent as ViewComponent;

class CoreController extends Controller {

    public function __construct(ViewComponent $view)
    {
        $this->view = $view;
    }

    public function getView($id) {

      // process_actions();
      // get();
        
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
                  "params": {
                    "description_content": {
                      "id" : 2,
                      "op" : "<>"
                    }
                  }
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