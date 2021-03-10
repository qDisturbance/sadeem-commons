# Sadeem / Commons

![sadeem-folder](art/folder-icon.png)

This is a collection of postgres models, migrations and controllers for common modules used in multiple projects

install using:

`composer require sadeem/commons`

Publish migrations, sample data and config:

`php artisan vendor:publish --provider="Sadeem\Commons\SadeemServiceProvider"`

Apply helper directories to:

`composer.json`

```json
"autoload": {
  "files": [
    "app/Helpers/Shared.php",
    "app/Helpers/Database.php",
    "app/Helpers/Constants.php"
  ],
  "psr-4": {
    ...
    ...
  }
},
```

## Models Documentation

- [City](documentation/CITY.md)
- [Country](documentation/COUNTRY.md)
- [Category](documentation/CATEGORY.md)


## Helpers Documentation

- [Shared](documentation/SHARED.md)
- [Database](documentation/DATABASE.md)


## Postgres required extensions

| Name | command | Description |
| :--- | :--- | :--- |
| UUID | `CREATE EXTENSION "uuid-ossp";` | uuid support for primary keys|
| PostGIS | `CREATE EXTENSION postgis;` | adds geospatial types to the db |
| Trigram | `CREATE EXTENSION pg_trgm;` | Levenstein (string similarity) functions |

## Before using:

run the following:

`php artisan storage:link`

`php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`

`php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"`

`php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"`

## Vendor Changes

---

for `spatie/permission` the published configuration `config/permissions.php` use the published models with uuid

change:
```php
[
  'models' => [
    'permission' => App\Models\Permission::class,
    'role' => App\Models\Role::class,
  ],
  'column_names' => [
    'model_morph_key' => 'model_uuid',
  ]
];
```
---

for `spatie/laravel-activitylog` migrations file `2021_00_00_000000_create_activity_log_table.php`

change:

```php
$table->nullableMorphs('subject', 'subject');
$table->nullableMorphs('causer', 'causer');
```

To:

```php
$table->nullableUuidMorphs('subject', 'subject');
$table->nullableUuidMorphs('causer', 'causer');
```

## Commands:

publishes the configuration
`php artisan sadeem:publish`

seeds data samples
`php artisan sadeem:seed-[model]` cities, countries or categories

## Seeding

by default, it will read from the published assets under `storage/app/public/sadeem/*.csv` files which you can edit prior to seeding
if that file doesn't exist or has been removed it will use the version in the package directory

## Configuration

inside your app root `config/sadeem.php`

```php
<?php

return [
  'route_prefixes' => [
    'cities' => '',
    'countries' => '',
    'categories' => '',
  ],
  'route_middlewares' => [
    'cities' => ['api'],
    'countries' => ['api'],
    'categories' => ['api'],
  ],
  
  'models' => [
    'city' => Sadeem\Commons\Models\City::class,
    'country' => Sadeem\Commons\Models\Country::class,
    'category' => Sadeem\Commons\Models\Category::class,
  ],
  
  'table_names' => [
    'cities' => 'cities',
    'countries' => 'countries',
    'categories' => 'categories',
    'model_has_categories' => 'model_has_categories',
  ],
  
  'column_names' => [
    'city_id' => 'city_id',
    'country_id' => 'country_id',
    'model_morph_key' => 'model_uuid',
  ],
  
  'table_timestamps' => [
    'cities' => false,
    'countries' => false,
    'categories' => false,
  ]
];
```

## LICENSE

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
