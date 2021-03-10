<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\CountryController;

$table = config('sadeem.table_names.countries');
Route::apiResource($table, CountryController::class)
  ->only('index', 'show');
