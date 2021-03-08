<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Category Configurations
  |--------------------------------------------------------------------------
  |
  | Used for the category model in the database migration and api resource
  | route included by default the api resource route prefix and
  | middleware can be configured and should implement a
  | set role or a can-edit type permissions
  |
  */
  'category_table_name' => 'categories',
  'category_table_timestamps' => false,
  'category_route_prefix' => 'someBoard',
  'category_route_middleware' => ['role:superadmin']

];
