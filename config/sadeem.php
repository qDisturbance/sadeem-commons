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

  /*
   * This trigger will connect the common modules like city, prayer times and weather
   * to the sadeem Dandelion service
   */
  'use_dandelion_resources' => true,

  'resource' => [
    'connection' => 'dandelion',
    'db_host' => '127.0.0.1',
    'db_port' => '5432',
    'db_name' => 'dandelion',
    'db_user' => 'roayaadmin',
    'db_password' => 'password',
    'db_schema' => 'public',
    'db_fdw' => 'postgres_fdw',
    'db_fdw_server' => 'dandelion',
    'tables' => [
      'cities' => 'cities',
      'weather' => 'weather'
    ],
  ],

  'route_as' => [
    'administration' => [
      'cities' => '',
      'countries' => '',
      'categories' => '',
    ],
    'readonly' => [
      'cities' => '',
      'countries' => '',
      'categories' => '',
    ]
  ],

  'route_prefixes' => [
    'administration' => [
      'cities' => '',
      'countries' => '',
      'categories' => '',
    ],
    'readonly' => [
      'cities' => '',
      'countries' => '',
      'categories' => '',
      'weather' => '',
    ]
  ],

  'route_middlewares' => [
    'administration' => [
      'cities' => ['api'],
      'categories' => ['api'],
      'weather' => ['api'],
    ],
    'readonly' => [
      'cities' => ['api'],
      'countries' => ['api'],
      'categories' => ['api'],
      'weather' => ['api'],
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
