# Database Helpers

a collection of helper methods for the database

## Search and Sort

any model can use the `searchAndSort(request)` function to handle the request using the following params:

| param | value | description |
| :--- | :--- | :--- |
| q | string | use similarity string search on name column |
| sort | asc / desc | order of sort |
| sort_by | column | helper function confirms the column by schema |
