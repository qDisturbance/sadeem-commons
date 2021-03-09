<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Category Configurations
  |--------------------------------------------------------------------------
  |
  | Used for database models migration and api resource
  | included by default the api resource route prefix
  |
  | accessible by localhost:8000/[prefix]/[model]
  | middleware  should implement a
  | set of permissions to a role
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

  'models' => [
//    'city' => App\Models\Role::class,
    'country' => Sadeem\Commons\Models\Country::class,
    'category' => Sadeem\Commons\Models\Category::class,
  ],

  'table_names' => [
    'cities' => 'cities',
    'countries' => 'countries',
    'categories' => 'categories',
    'model_has_categories' => 'model_has_categories',
  ],

  'column_names' => [
    'model_morph_key' => 'model_uuid',
    'country_id' => 'country_id'
  ],

  'table_timestamps' => [
    'cities' => false,
    'countries' => false,
    'categories' => false,
  ]
];
