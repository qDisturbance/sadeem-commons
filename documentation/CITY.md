# City

a city model with a geography point location field

## Seed Command

`php artisan sadeem:seed-cities`

---

## Routes

## `index`

can be searched using the `q` parameter

| Parameters | value | Description |
| :--- | :--- | :--- |
| q | string | searches the default index (usually name) |
| sort | table column | accepts any table column, add `-` to order by `desc` like `-name` |

---


## Main table structure

`countries`

| column | type |
| :--- | :--- |
| id | uuid |
| name | string |
| is_disabled | boolean |
| location | point (Geography, srid=4326) |

## Usage

`composer require mstaack/laravel-postgis`

location points can be created using the postgis types from the mstaack library:

`$location = new Point('32.1201805', '20.0863881');`

## Trait

`use HasCity;` inside a class to define a `hasOne` relation using `city()`


---

## Data Samples

published dir: `storage/app/public/sadeem/cities.csv`

package dir: `resources/assets/cities.csv`

id | name | is_disabled | location |
| :--- | :--- | :--- | :--- |
d8ad1c8b-9e9e-46cb-a0f8-140455f8e617 | جالو    | f | 0101000020E610000055849B8C2A7749C07EC9C6832D684440 |
7ccc71b4-f992-4d98-9505-9c0c556e3e1f | البيضاء | f | 0101000020E6100000730F09DFFB815740F1A1444B1E894640 |
c44dc24e-2358-499e-b601-f4a0c3fac6d3 | طبرق    | f | 0101000020E6100000D9243FE2576E6240E146CA1649534CC0 |
78785481-34fa-4eaf-81dc-4442eaa07543 | بنغازي  | f | 0101000020E6100000992B836A83B747C0EED11BEE234C52C0 |
2faf0770-b72b-4be2-808d-6ddf27868824 | طرابلس  | f | 0101000020E61000008D62B9A5D55D53400B7DB08C0D654140 |
6d3c37d2-2137-421e-a2df-4d22d5c8a130 | المرج   | f | 0101000020E6100000514CDE00B34365402A7288B839D34740 |
b7ab6225-880c-4347-8a68-93a6b937fac6 | اجدابيا | f | 0101000020E6100000EA5A7B9FAAFA5AC022C2BF081AF334C0 |
4f1937b6-354e-47c6-9fa3-25b16ab21bfa | درنه    | f | 0101000020E6100000B98D06F016636540C537143E5B5948C0 |
