

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

## Configuration

inside your app root `config/sadeem.php`

| variable | default | description |
| :--- | :--- | :--- |
| category_table_name | categories | migration table name and api resource path |
| category_table_timestamps | false | enable or disable timestamps |
| category_route_prefix | someBoard | route prefix for the api resource |
| category_route_middleware | ['role:superadmin'] | middleware for the api resource |

## Usage

the category model 

| column | type | description |
| :--- | :--- | :--- |
| id | uuid | identifier |
| name | string | category name |
| is_disabled | boolean | status |
| parent_id | uuid | null on top level categories, has value of a higher level category on sub levels|
| model_name | string | model to associate category with (only for top level categories), null on lower level categories |

### example 

| id | name | is_disabled | parent_id | model_name |
| :--- | :--- | :--- | :--- | :--- |
| 1 | ***fruits*** | false | null | food | 
| 2 | ***vegetables*** | false | null | food |
| 3 | apples | false | 1 | null |
| 4 | oranges | false | 1 | null |
| 5 | lettuce | false | 2 | null |
| 6 | cucumbers | false | 2 | null |

### api resource routes available:

index: categories model can use the `searchAndSort(request)` function to handle the request using the following params:

| param | value | description |
| :--- | :--- | :--- |
| q | string | use similarity string search on name column |
| sort | asc / desc | order of sort |
| sort_by | column | helper function confirms the column by schema |

### Trait

`use HasCategories;` inside a class to define a `hasMany` relation using `categories()`

## Postgres required extensions

| Name | command | Description |
| :--- | :--- | :--- |
| UUID | `CREATE EXTENSION "uuid-ossp";` | uuid support for primary keys|
| Trigram | `CREATE EXTENSION pg_trgm;` | Levenstein (string similarity) functions |



## LICENSE

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
