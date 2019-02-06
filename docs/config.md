# Config

## The import statment


## Lenguage description


### Root
The root element it's an object with view attributes.

```json

{
    "view1" : "import view1",
    "view2" : "import view2"
}

```

### View
The view element normally describes a page in the frontend but in fact it's just a wrapper of things and can be used for other reasons.
 

```json

{
    "view" : "name of the view, it's just an identifier"

}

```



```json

{
    "model" : {
        "description" :  "if it's a table it's the name of the table, else it's a name that it will appear for the frontend as a model but won't accept CUD operations",
        "optional" : true,
        "accepts" : ["string", "object"]  
    },
    "attribute" : "name of the atribute in the model"

}

```
