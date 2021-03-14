<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Sadeem Commons Configurations
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
    'edit' => [
      'cities' => '',
      'countries' => '',
      'categories' => '',
    ],
    'read' => [
      'cities' => '',
      'countries' => '',
      'categories' => '',
    ]
  ],

  'route_middlewares' => [
    'edit' => [
      'cities' => ['api'],
      'categories' => ['api'],
    ],
    'read' => [
      'cities' => ['api'],
      'countries' => ['api'],
      'categories' => ['api'],
    ]
  ],

  'models' => [
    'city' => Sadeem\Commons\Models\City::class,
    'country' => Sadeem\Commons\Models\Country::class,
    'category' => Sadeem\Commons\Models\Category::class,
  ],

  'model_names' => [
    'city' => 'city',
    'country' => 'country',
    'category' => 'category'
  ],

  'table_names' => [
    'cities' => 'cities',
    'countries' => 'countries',
    'categories' => 'categories',
    'model_has_categories' => 'model_has_categories',
  ],

  'column_names' => [
    'city_id' => 'city_id',
    'country_id' => 'country_id',
    'model_morph_key' => 'model_uuid',
  ],

  'table_timestamps' => [
    'cities' => true,
    'countries' => false,
    'categories' => false,
  ]
];
