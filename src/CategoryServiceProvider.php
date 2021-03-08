<?php

namespace Sadeem\Commons;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Sadeem\Commons\Commands\SeedCategory;
use Sadeem\Commons\Commands\PublishConfig;

class CategoryServiceProvider extends ServiceProvider
{
  public function boot()
  {
    $this->registerRoutes();

    if ($this->app->runningInConsole()) {

      $this->commands([
        PublishConfig::class,
        SeedCategory::class
      ]);

      $this->publishResources();
    }
  }

  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/../config/category.php', 'sadeem');
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
      'prefix' => config('sadeem.category_route_prefix'),
      'middleware' => config('sadeem.category_route_middleware'),
    ];
  }

  protected function publishResources()
  {
    $this->publishes([
      __DIR__ . '/../config/category.php' => config_path('sadeem.php'),
    ], 'config');

    if (!class_exists('CreateCategoriesTable')) {
      $timestamp = date('Y_m_d_His', time());

      $this->publishes([
        __DIR__ . '/../database/migrations/create_categories_table.php.stub' => database_path("/migrations/{$timestamp}_create_categories_table.php"),
      ], 'migrations');
    }

    if ($this->app->runningInConsole()) {
      $this->publishes([
        __DIR__.'/resources/assets' => public_path('sadeem_tech'),
      ], 'assets');
    }
  }
}
