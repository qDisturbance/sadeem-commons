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

## Before you use:

run the following:

`php artisan storage:link`

`php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`

`php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"`

`php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"`

## Vendor Spatie Changes
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

seeds with category samples
`php artisan sadeem:seed-categories`

## Seeding

by default, it will read from the published assets under `public/sadeem/*.csv` files which you can edit prior to seeding
if that file doesn't exist or has been removed it will use the version in the package directory

## Configuration

inside your app root `config/sadeem.php`

```json
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

'table_names' => [
  'cities' => 'cities',
  'countries' => 'countries',
  'categories' => 'categories',
  'model_has_categories' => 'model_has_categories',
],

'column_names' => [
  'model_morph_key' => 'model_uuid',
],

'table_timestamps' => [
  'cities' => false,
  'countries' => false,
  'categories' => false,
]
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
| Trigram | `CREATE EXTENSION pg_trgm;` | Levenstein (string similarity) functions |

## LICENSE

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
