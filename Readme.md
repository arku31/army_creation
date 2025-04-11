This is a simple test task that is simulating an army creation. There are several ways to run it

# Description
The code is a simple PHP script that generates a random army of troops. 
The troops consist of units of the same type. These units are defined in Domain\Army\Units and they have their unique attributes.
Available units are easy to add or remove - just create another unit following the same structure. After that, add it to the AvailableUnitsRegistry.

The DDD principle is used in the code. The code is divided into several layers:
- **Domain**: Contains the core logic of the application. It defines the entities, value objects, and repositories.
- **Application**: Contains the application logic. Well.. One file for now.
- **Infrastructure**: May contain the implementation of the repositories and other infrastructure-related code. In this case, it contains the implementation of RandomTroopDistributer.
- **Tests**: Contains the unit tests for the application. The tests are written using PHPUnit.
- **Presentation** : Contains the presentation layer of the application. It contains the CLI and API implementations.
- **api.php** and **cli.php**: These files are the entry points for the application. They handle the input and output of the application.


The number of tests are limited as this is still a test task :)

# Prerequisites
- Run `composer install` to install the dependencies.

# Running the CLI

To run it, simply call the php file.
```
    php `cli.php` {number}. The number should represent the army size.         
```

Sample output:

```
Generated Army size 50:
9 Archer
17 Horseman
11 Swordsman
13 Spearman
```

# Running in the browser

To run it in the browser, you can use the built-in PHP server. Run the following command in the terminal:
```
    php -S localhost:8000
```
Then, open your browser and go to `http://localhost:8000/api.php?army_size=50`. The number should represent the army size.

Sample output for army size 5:

```
{
    "armySize": 5,
    "troops": [
        {
            "count": 1,
            "unitType": "Archer",
            "units": [
                {
                    "troopType": "RANGED",
                    "health": 40,
                    "attack": 51,
                    "defense": 3
                }
            ]
        },
        {
            "count": 2,
            "unitType": "Horseman",
            "units": [
                {
                    "troopType": "CAVALRY",
                    "health": 217,
                    "attack": 31,
                    "defense": 12
                },
                {
                    "troopType": "CAVALRY",
                    "health": 238,
                    "attack": 31,
                    "defense": 10
                }
            ]
        },
        {
            "count": 1,
            "unitType": "Swordsman",
            "units": [
                {
                    "troopType": "MELEE",
                    "health": 158,
                    "attack": 15,
                    "defense": 15
                }
            ]
        },
        {
            "count": 1,
            "unitType": "Spearman",
            "units": [
                {
                    "troopType": "MELEE",
                    "health": 95,
                    "attack": 12,
                    "defense": 13
                }
            ]
        }
    ]
}
```