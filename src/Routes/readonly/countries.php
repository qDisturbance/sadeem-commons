<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\PublicCountryController;

$table = config('sadeem.table_names.countries');

Route::apiResource($table, PublicCountryController::class)
  ->only('index', 'show');
