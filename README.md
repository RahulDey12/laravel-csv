<p align="center"><img src="/art/logo.svg" alt="Logo Laravel CSV"></p>

Laravel CSV is a supercharged ⚡ CSV reader with the sweets of Laravel
Eloquent.

> **NOTE:** This is very early stage of this package so do not use
> this package to production *API* may have change in the feature.

### What needs to be done in this package:

- [x] Add `Where` Clause to the `Builder`
- [ ] Add `OrderBy` to the `Builder`
- [x] Add Sanitization support
- [ ] Add support for casting
- [ ] Add file saving support
- [ ] Add enum support
- [ ] Add Tests

## Installation

> Requires [PHP 8.0+](https://www.php.net/releases/)

Require Laravel CSV using [Composer](https://getcomposer.org/):

```shell
composer require rahul900day/laravel-csv
```

## Usage

```php
use Rahul900day\Csv\Csv;

// Print 3 Passenger Name age above 20
Csv::fromPath('titanic.csv')
    ->query()
    ->where('Age', '>', 20)
    ->willBeSanitized()
    ->lazy()
    ->take(3)
    ->each(function ($passenger) {
        echo $passenger->Name
    });
```
