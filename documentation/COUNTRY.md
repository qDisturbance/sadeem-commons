# Country

model for country standard iso naming, phone numbers and country code
a zip file for flags can be downloaded from here and placed anywhere
in the project: [flags.zip](/src/resources/assets/flags.zip)

publish the assets using:

`php artisan storage:link`

`php artisan vendor:publish --provider="Sadeem\Commons\SadeemServiceProvider" --tag="assets"`


## Seed Command
`php artisan sadeem:seed-countries`

---
## Routes

## `index`

can be searched using the `q` parameter in arabic and english for country names

| Parameters | value | Description |
| :--- | :--- | :--- |
| q | string | searches arabic and english country names |
| sort | table column | accepts any table column, add `-` to order by `desc` like `-name` |
| include | icon | adds the icon path for the country flag in the response |


---

## Usage 

unzip the flags archive and make sure constants' helper is published inside

`app\Helpers\Constants.php`

`php artisan vendor:publish --provider="Sadeem\Commons\SadeemServiceProvider" --tag="helpers"`

to get the png file you can match the iso field with the filename like:



and include it in the resource using:

`$iconFileName = strtolower($country->iso).'.png';`

`storage_path(("app/public/sadeem/flags/{$iconSize}x{$iconSize}/{$iconFileName}"))`



## Main table structure

`countries`

| column | type |
| :--- | :--- |
| id | increments |
| iso | string(2) |
| iso3 | string(3) |
| num_code | smallInteger |
| phone_code | integer |
| name | string(80) |
| en_name | string(80) |
| ar_name | string(80) |


## Trait

`use HasCountry;` inside a class to define a `morphToMany` relation using `country()`

---

## Data Samples

published dir: `storage/app/public/sadeem/countries.csv`

package dir: `resources/assets/countries.csv`

id | iso | iso3 | name | en_name | ar_name | num_code | phone_code |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | AF | AFG | AFGHANISTAN | Afghanistan | أفغانستان | 4 | 93 |
| 2 | AL | ALB | ALBANIA | Albania | ألبانيا |  8 |  355 |
| 3 | DZ | DZA | ALGERIA | Algeria | الجزائر | 12 |   213 |
| ... | ... | ... | ... | ... | ... | ... | ... |
