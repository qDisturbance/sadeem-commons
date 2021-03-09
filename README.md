

# Sadeem / Commons 

![sadeem-folder](art/folder-icon.png)

This is a collection of postgres models, migrations and controllers for common modules used in multiple projects

install using:

`composer require sadeem/commons` 

Publish migrations, sample data and config:

`php artisan vendor:publish --provider="Sadeem\Commons\CategoryServiceProvider"`

## Before you use:
for `spatie/laravel-activitylog` migrations file `2021_00_00_000000_create_activity_log_table.php` change:
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

| variable | default | description |
| :--- | :--- | :--- |
| table_names | cities | model table name |
|  | countries | model table name |
|  | categories | model table name |
| table_timestamps | false | enable or disable timestamps |
| category_route_prefix | someBoard | route prefix for the api resource |
| category_route_middleware | ['api'] | middleware for the api resource |

## Models

### category

| column | type | description |
| :--- | :--- | :--- |
| id | uuid | identifier |
| name | string | category name |
| is_disabled | boolean | status |
| parent_id | uuid | null on top level categories, has value of a higher level category on lower levels|

### model_has_category

| column | type | description |
| :--- | :--- | :--- |
| model_uuid | uuid | identifier |
| category_id | string | category name |
| model_type | boolean | status |

### Data Samples 

| id | name | is_disabled | parent_id |
| :--- | :--- | :--- | :--- |
| 1 | ***fruits*** | false | null |
| 2 | ***vegetables*** | false | null |
| 3 | apples | false | 1 |
| 4 | oranges | false | 1 |
| 5 | lettuce | false | 2 |
| 6 | cucumbers | false | 2 |

### api resource routes available:

index: categories model can use the `searchAndSort(request)` function to handle the request using the following params:

| param | value | description |
| :--- | :--- | :--- |
| q | string | use similarity string search on name column |
| sort | asc / desc | order of sort |
| sort_by | column | helper function confirms the column by schema |

### Trait

`use HasCategories;` inside a class to define a `morphToMany` relation using `categories()`

## Postgres required extensions

| Name | command | Description |
| :--- | :--- | :--- |
| UUID | `CREATE EXTENSION "uuid-ossp";` | uuid support for primary keys|
| Trigram | `CREATE EXTENSION pg_trgm;` | Levenstein (string similarity) functions |



## LICENSE

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
