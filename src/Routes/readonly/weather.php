<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\PublicWeatherController;

$table = config('sadeem.table_names.weather');
$model = config('sadeem.model_names.weather');

Route::apiResource($table, PublicWeatherController::class)->only(['index', 'show']);
