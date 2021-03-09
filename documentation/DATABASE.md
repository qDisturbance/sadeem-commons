# Helper / Database

a collection of helper methods for the database

## Search and Sort (in the Model for now)

any model can use the `searchAndSort(request)` function to handle the request using the following params:

| param | value | description |
| :--- | :--- | :--- |
| q | string | use similarity string search on name column |
| sort | -name,is_disabled | column name with '-' sign as desc order direction |

---

## similarityByColumn

accepts: `$modelInstance`, `$column`, `$q`

String search using `pg_trgm` similarity functions


---

## Order Query

accepts: `$modelInstance`, `sorts`

can string multiple columns in sort param

and uses `-` sign infront of the column to set it to `desc` 

`?sort=-name,age,-is_disabled`

---

## IsDisabled Switch

accepts: `$modelInstance`

every model has a `is_disabled` switch

this flips the state 

---

## Confirm Columns
