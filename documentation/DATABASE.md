# Helper / Database

a collection of helper methods for the database

## Search and Sort

any model can use the `searchAndSort(request)` function to handle the request using the following params:

| param | value | description |
| :--- | :--- | :--- |
| q | string | use similarity string search on name column |
| sort | -name,is_disabled | column name with '-' sign as desc order direction |
