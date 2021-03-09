<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Category Configurations
  |--------------------------------------------------------------------------
  |
  | Used for the category model in the database migration and api resource
  | route included by default the api resource route prefix
  |
  | accessible through localhost:8000/someBoard/categories
  | middleware can be configured and should implement a
  | set role or a can-edit type permissions
  |
  | do not change the name and timestamps
  | after initial migration
  |
  */
  'route_prefixes' => [
    'cities' => '',
    'countries' => '',
    'categories' => '',
  ],

  'route_middlewares' => [
    'cities' => ['api'],
    'countries' => ['api'],
    'categories' => ['api'],
  ],

// future implementation, not ready for use
//
//  'models' => [
//    'city' => App\Models\Role::class,
//    'country' => App\Models\Role::class,
//    'category' => Sadeem\Commons\Models\Category::class,
//  ],

  'table_names' => [
    'cities' => 'cities',
    'countries' => 'countries',
    'categories' => 'categories',
    'model_has_categories' => 'model_has_categories',
  ],

  'column_names' => [
    'model_morph_key' => 'model_uuid',
  ],

  'table_timestamps' => [
    'cities' => false,
    'countries' => false,
    'categories' => false,
  ]
];
