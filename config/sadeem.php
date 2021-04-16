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
  'use_dandelion_resources' => false,

  'resource' => [
    'connection' => 'dandelion',
    'db_host' => env('postgres_fdw_host'),
    'db_port' => env('postgres_fdw_port'),
    'db_name' => env('postgres_fdw_db'),
    'db_superuser' => env('DB_USERNAME'),
    'db_user' => env('postgres_fdw_user'),
    'db_password' => env('postgres_fdw_pass'),
    'db_schema' => 'public',
    'db_fdw' => 'postgres_fdw',
    'db_fdw_server' => 'dandelion',
    'tables' => [
      'cities' => 'cities',
      'weather' => 'weather',
      'countries' => 'countries',
      'prayer_times' => 'prayer_times'
    ],
  ],

  'route_as' => [
    'administration' => [
      'cities' => '',
      'countries' => '',
      'categories' => '',
      'weather' => '',
      'prayer_times' => '',
    ],
    'readonly' => [
      'cities' => '',
      'countries' => '',
      'categories' => '',
      'weather' => '',
      'prayer_times' => '',
    ]
  ],

  'route_prefixes' => [
    'administration' => [
      'cities' => '',
      'countries' => '',
      'categories' => '',
      'weather' => '',
      'prayer_times' => '',
    ],
    'readonly' => [
      'cities' => '',
      'countries' => '',
      'categories' => '',
      'weather' => '',
      'prayer_times' => '',
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
      'prayer_times' => ['api'],
    ]
  ],

  'models' => [
    'city' => Sadeem\Commons\Models\City::class,
    'country' => Sadeem\Commons\Models\Country::class,
    'category' => Sadeem\Commons\Models\Category::class,
    'weather' => Sadeem\Commons\Models\Weather::class,
    'prayer_times' => Sadeem\Commons\Models\PrayerTime::class,
  ],

  'model_names' => [
    'city' => 'city',
    'country' => 'country',
    'category' => 'category',
    'weather' => 'weather',
    'prayer_time' => 'prayer_time'
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
