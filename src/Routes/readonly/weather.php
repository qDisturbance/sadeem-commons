<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\PublicWeatherController;

$table = config('sadeem.table_names.weather');

Route::apiResource($table, PublicWeatherController::class)->only(['index']);
