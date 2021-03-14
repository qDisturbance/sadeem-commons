<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\CityController;

$table = config('sadeem.table_names.cities');
$model = config('sadeem.model_names.city');

Route::apiResource($table, CityController::class);
Route::patch("{$table}/{{$model}}/toggle", [CityController::class, 'toggle'])
  ->name("{$table}.toggle");
