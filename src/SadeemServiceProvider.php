<?php

namespace Sadeem\Commons;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Commands\SeedCity;
use Sadeem\Commons\Commands\SeedCountry;
use Sadeem\Commons\Commands\SeedCategory;
use Sadeem\Commons\Commands\PublishConfig;

class SadeemServiceProvider extends ServiceProvider
{
  public function boot()
  {
    $this->registerRoutes();

    if ($this->app->runningInConsole()) {

      $this->commands([
        PublishConfig::class,
        SeedCategory::class,
        SeedCountry::class,
        SeedCity::class
      ]);

      $this->publishResources();
    }
  }

  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/../config/sadeem.php', 'sadeem');
  }

  public function registerRoutes()
  {
    Route::group($this->routeConfiguration(config('sadeem.table_names.cities')), function () {
      $this->loadRoutesFrom(__DIR__ . '/Routes/cities.php');
    });
    Route::group($this->routeConfiguration(config('sadeem.table_names.countries')), function () {
      $this->loadRoutesFrom(__DIR__ . '/Routes/countries.php');
    });
    Route::group($this->routeConfiguration(config('sadeem.table_names.categories')), function () {
      $this->loadRoutesFrom(__DIR__ . '/Routes/categories.php');
    });
  }

  protected function routeConfiguration($tableName)
  {
    return [
      'prefix' => config("sadeem.route_prefixes.{$tableName}"),
      'middleware' => config("sadeem.route_middlewares.{$tableName}"),
    ];
  }

  protected function publishResources()
  {
    $timestamp = date('Y_m_d_His', time());

    $this->publishes([__DIR__ . '/../config/sadeem.php' => config_path('sadeem.php')], 'config');
    $this->publishes([__DIR__ . '/resources/assets' => storage_path("app/public/sadeem")], 'assets');

    $this->publishes([
      __DIR__ . '/Helpers/Shared.php' => app_path('Helpers/Shared.php'),
      __DIR__ . '/Helpers/Database.php' => app_path('Helpers/Database.php'),
      __DIR__ . '/Helpers/Constants.php' => app_path('Helpers/Constants.php'),
    ], 'helpers');

    if (!class_exists('CreateCategoriesTable')
      && !class_exists('CreateCountriesTable')
      && !class_exists('CreateCountriesTable')) {

      $this->publishes([
        __DIR__ . '/../database/migrations/create_cities_table.php.stub' => database_path("/migrations/{$timestamp}_create_cities_table.php"),
        __DIR__ . '/../database/migrations/create_countries_table.php.stub' => database_path("/migrations/{$timestamp}_create_countries_table.php"),
        __DIR__ . '/../database/migrations/create_categories_table.php.stub' => database_path("/migrations/{$timestamp}_create_categories_table.php"),
      ], 'migrations');
    }
  }
}
