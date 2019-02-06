# Config

## The import statment

At any point in the json file instead of assigning a value, an import statement can be used. This will have the form of "import file". The file must be a .json located inside app/storage/config (can be located further inside this folder as long as the path is specified).
These files follow the same rules and can use an import statement at any point.

An example:

```json

"children": {
  "0": {
    "type": "description",
    "multiple" : true,
    "values": {
      "description_title": "_lang",
      "description_content": {
        "query": "import query"
      }
    }
  }
}
             
```

Where query:

```json

{
  "model": "user",
  "attribute" : "name",
  "function": "select",
  "inputs" : [{
    "name" : "id",
    "op" : "<>"
  }],
  "build" : {
    "orderBy" : "'name', 'asc'",
    "take" : 2
  }
}

```