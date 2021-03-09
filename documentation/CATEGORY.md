# Category

a versatile categorizing model that can have deep nesting as a project requires

the model is recursive on itself while the row has a `parent_id` and builds the parent category path to deliver it in the model resource

## Seed Command

`php artisan sadeem:seed-categories`

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

`categories`

| column | type | description |
| :--- | :--- | :--- |
| id | uuid | identifier |
| name | string | category name |
| is_disabled | boolean | status |
| parent_id | uuid | null on top level categories, has value of a higher level category on lower levels|

## Morph relation table

`model_has_category`

| column | type | example |
| :--- | :--- | :--- |
| model_uuid | uuid | product_id |
| category_id | uuid | category_id |
| model_type | boolean | App\Models\Product |

## Trait

`use HasCategories;` inside a class to define a `morphToMany` relation using `categories()`

---

## Data Samples

published dir: `storage/app/public/sadeem/categories.csv`

package dir: `resources/assets/categories.csv`

| id | name | is_disabled | parent_id | path |
| :--- | :--- | :--- | :--- | :--- |
| 1 | ***fruits*** | false | null | fruits |
| 2 | ***vegetables*** | false | null | vegetables |
| 3 | apples | false | 1 | fruits / apples |
| 4 | oranges | false | 1 | fruits / oranges |
| 5 | lettuce | false | 2 | vegetables / lettuce |
| 6 | cucumbers | false | 2 | vegetables / cucumbers |
| 7 | green | false | 3 | fruits / apples / green |
| 8 | yellow | false | 3 | fruits / apples / yellow |
| 9 | red | false | 3 | fruits / apples / red |

## Data Response Sample

```json
[
  {
    "id": "bfa012d0-eaed-41e3-a0aa-77841e7ea36b",
    "name": "fruits",
    "is_disabled": false,
    "parent": []
  },
  {
    "id": "cde492b4-dc0f-40bd-b6c6-330630135def",
    "name": "apples",
    "is_disabled": true,
    "parent": [
      "fruits"
    ]
  },
  {
    "id": "cfb0c0cb-c664-4621-9c81-efef0c6efc2e",
    "name": "red",
    "is_disabled": false,
    "parent": [
      "apples",
      "fruits"
    ]
  },
  {
    "id": "f6887cc7-039b-4b25-ad93-f794cab21e72",
    "name": "yellow",
    "is_disabled": true,
    "parent": [
      "apples",
      "fruits"
    ]
  },
  {
    "id": "fb06b7d0-f6fd-4b6f-9bec-3c45826f8bf8",
    "name": "green",
    "is_disabled": true,
    "parent": [
      "apples",
      "fruits"
    ]
  }
]
```
