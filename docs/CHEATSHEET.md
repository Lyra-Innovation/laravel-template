Or `How to build a impolute config.json`

# Building blocks

## Events

To dispatch a Redux action when a component event is emitted
Special always available events: **init** and **destroy**

```json
{
  "type": "button",
  "values": {},
  "events": {
    // 'button' component has an event with the name 'click'
    "click": [{
      "action": "SetVariable",
      "params": {
        "name": "myFrontendVariableName",
        // object dispatched by 'button' component has a 'times' property, which will susbtitute this value
        "value": "$event.times"
      }
    }, {
      "bubble": "closeDialog",
      "params": {
        "save": true
      }
    }],
    "init": {
      ...
    },
    "destroy": {
      ...
    }
  }
}
```

## Actions

Available `action` values: `ModelAction`, `SetVariable`, `NavigateRoute`, `ShowNotification`, details in `presenter/lib/state/presenter.actions.ts` in function `createAction`

```json
{
  "events": {
    "click": [
      {
        "action": "ModelAction",
        "params": {
          "action": "create",
          "model": "user",
          "params": {
            "name": "Martin",
            "email": "test3@test.com",
            "password": 1234
          }
        }
      }
    ]
  }
}
```

## Dynamic select

### \$me

Is substituted by the logged user's id

```json
{
  "values": {
    "userId": "$me"
  }
}
```

### By model

```json
{
  "model": "users",
  "attribute": "name",
  "id": "0"
}
```

### By scopes

```json
{
  "scope": "route", // Possible scopes: 'route', 'local', 'view', 'global'
  "select": "modelid" // Name of the variable to select from the scope
}
```

Available in:

- `values`

```json
"values": {
  "text": {
    "scope": "route",
    "select": "userid"
  }
}
```

- `inputs.selectFrom`

```json
"inputs": [{
  "name": "name",
  "selectFrom": {
    "scope": "route",
    "select": "userid"
  }
}]
```

- `events.params`

```json
"eventsName": [{
  "action": "NavigateRoute",
  "params": {
    "route": {
      "scope": "view",
      "select": "selectedUserId
    }
  }
}]
```

## Query inputs

- How to send a static input value

```json
"values": {
  "text": {
    "query": {
      "model": "user",
      "attribute": "name",
      "function": "select",
      "inputs": [{
        "name": "name",
        "value": "Martin",
        "build" : {
          "orderBy" : "'name', 'asc'",
          "take" : 2
        }
      }]
    }
  }
}
```

- How to select a dynamic input value

```json
"values": {
  "text": {
    "query": {
      "model": "user",
      "attribute": "name",
      "function": "select",
      "inputs": [{
        "name": "name",
        "selectFrom": {
          "scope": "route",
          "select": "modelid"
        }
      }]
    }
  }
}
```

## Import

This code imports `anotherView.json` and puts its code in place of the `import` command:

```json
{
  "children": {
    "0": "import anotherView"
  }
}
```

# Advanced

## Routes

**Precondition**: the parent of the rendered component has to contain a `<router-outlet>` element.

```json
{
  "type": "list-layout",
  "values": {},
  "route": "home/:modelid" // ':modelid' will be converted to a variable available to dynamic select
}
```

## Forms

```json
{
  "type": "form-group",
  "values": {},
  "children": {
    "name": {
      "type": "input",
      "values": {
        "controlName": "name",
        "initialValue": "",
        // Angular validators reference: https://angular.io/api/forms/Validators
        "validators": "Validators.required,Validators.minLength(4)"
      }
    },
    "submit": {
      "type": "button",
      "values": {
        "text": "SUBMIT",
        "type": "submit"
      }
    }
  },
  "events": {
    "formSubmitted": [
      {
        "action": "ModelAction",
        "params": {
          "action": "create",
          "params": {
            "name": "$event.value.name" // 'name' has to be in a controlName of the children of the form
          }
        }
      }
    ]
  }
}
```
