
# Category

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

published dir: `public/sadeem/categories.csv`

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
