<?php

namespace Sadeem\Commons;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Sadeem\Commons\Commands\SeedCategory;
use Sadeem\Commons\Commands\PublishConfig;
use Sadeem\Commons\Commands\SeedCountry;

class SadeemServiceProvider extends ServiceProvider
{
  public function boot()
  {
    $this->registerRoutes();

    if ($this->app->runningInConsole()) {

      $this->commands([
        PublishConfig::class,
        SeedCategory::class,
        SeedCountry::class
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
    Route::group($this->routeConfiguration(), function () {
      $this->loadRoutesFrom(__DIR__ . '/routes.php');
    });
  }

  protected function routeConfiguration()
  {
    return [
      'prefix' => config('sadeem.route_prefixes.categories'),
      'middleware' => config('sadeem.route_middlewares.categories'),
    ];
  }

  protected function publishResources()
  {
    $this->publishes([
      __DIR__ . '/Helpers/Shared.php' => app_path('Helpers/Shared.php'),
    ], 'helpers');

    $this->publishes([
      __DIR__ . '/Helpers/Database.php' => app_path('Helpers/Database.php'),
    ], 'helpers');

    $this->publishes([
      __DIR__ . '/Helpers/Constants.php' => app_path('Helpers/Constants.php'),
    ], 'helpers');

    $this->publishes([
      __DIR__ . '/../config/sadeem.php' => config_path('sadeem.php'),
    ], 'config');

    if (!class_exists('CreateCategoriesTable')) {
      $timestamp = date('Y_m_d_His', time());

      $this->publishes([
        __DIR__ . '/../database/migrations/create_categories_table.php.stub' => database_path("/migrations/{$timestamp}_create_categories_table.php"),
      ], 'migrations');
    }
    if (!class_exists('CreateCountriesTable')) {
      $timestamp = date('Y_m_d_His', time());

      $this->publishes([
        __DIR__ . '/../database/migrations/create_countries_table.php.stub' => database_path("/migrations/{$timestamp}_create_countries_table.php"),
      ], 'migrations');
    }

    if ($this->app->runningInConsole()) {
      $this->publishes([
        __DIR__.'/resources/assets' => storage_path("app/public/sadeem"),
      ], 'assets');
    }
  }
}
