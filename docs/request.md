# Request

## Actions

Actions is an array of objects where each object is an action to execute in the database. There are 3 types of actions:

### Create

Insert into the database

```json

{
      "action": "create",			// Name of the action (string)
      "model": "user",				// Name of the table (string)
      "params": {				// Parameters to insert in the database (object)
        "name": "Martin",
        "email": "martin@test.com",
        "password": 1234
      }
}

```

### Update

Updates records of the database

#### Single update

```json

{
      "action": "update",			// Name of the action (string)
      "model": "user",				// Name of the table (string)
      "params": {				// Parameters to update in the database, it must contain an id (object)
        "id": 10,
        "name": "Maria",
        "email": "maria@test.com"
      }
}

```

#### Mass update

```json

{
      "action": "update",			// Name of the action (string)
      "model": "car",				// Name of the table (string)
      "params": {				// Parameters to update in the database (object)
        "color": "Red",
        "price": "3000"
      },
      "where": {				// Records in the database that match all of these parameters will be updated (object)
        "color": "Yellow",
        "year": "2015"
      }
}

```

### Delete

Deletes records of the database

```json

{
      "action": "delete",			// Name of the action (string)
      "model": "car",				// Name of the table (string)
      "where": {				// Records in the database that match all of these parameters will be deleted (object)
        "color": "Yellow",
        "year": "2015"
      }
}

```
