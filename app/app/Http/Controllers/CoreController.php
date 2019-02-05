<?php

namespace App\Http\Controllers;

use App\ViewComponent as ViewComponent;
use App\ActionManager as ActionManager;

class CoreController extends Controller {

    public function __construct(ViewComponent $view, ActionManager $action) 
    {
        $this->viewManager = $view;
        $this->actionManager = $action;
    }

    public function getView($id) {

      // process_actions();
      // get();
        
    }

    public function getConfig() {

        $inputString = '{
          "views": {
            "playerprofile": {
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
                          "id": 2,
                          "op" : "<>"
                        }
                      },
                      "children": {}
                    }
                  }
                }
              }
            }
          },
          "actions": [
            {
              "action": "create",
              "model": "user",
              "params": {
                "name": "Martin",
                "email": "test3@test.com",
                "password": 1234
              }
            },
            {
              "action": "update",
              "model": "user",
              "params": {
                "name": "Martin",
                "email": "test2@test.com"
              }
            },
            {
              "action": "delete",
              "model": "user",
              "where": {
                "id": 10
              }
            }
          ]
        }
        ';

        $input = json_decode($inputString);
        $this->actionManager->processActions($input->actions);
        return json_encode($this->viewManager->merge($input));
    }

}