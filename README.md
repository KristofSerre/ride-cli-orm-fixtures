# Ride: ORM CLI Fixtures

This module adds a way to create dummy data for Data types

##Don't forget to install this module first

 **"fzaninotto/faker": "^1.6@dev",**

## Fixtures load
This command generates dummy entries in the database for the defined models.

**alias** : fl


##JSON File
Create a json file in your application/config folder

firstly write your entries without any relations.
Secondly the Entries that use these relations.

```
{
    "locale": "be_BE",  //Sets the locale for your dummy entries
    "fixtures": [
        {
            "entry": "Child",
            "amount": 5,
            "fields": {
                "firstName": "firstName",
                "lastName": "lastName"
            }
        },
        {
            "entry": "Dummy",
            "amount": 20,
            "fields": {
                "firstName": "firstName",
                "lastName": "lastName",
                "city": "city",
                "email": "email",
                "children": {
                    "entry": "Child",
                    "amount": 3
                },
                "image": {
                    "entry": "Asset",
                    "amount" : 1,
                    "fields": {
                        "type": "image",
                        "name": "image",
                        "value": "http://www.newton.ac.uk/files/covers/968361.jpg"
                    }
                },
                "category": {
                    "entry": "TaxonomyTerm",
                    "amount": 3,
                    "vocabulary": 1
                },
                "description": "realText"
            }
        }

    ]
```