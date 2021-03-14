<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\PublicCityController;

$table = config('sadeem.table_names.cities');
$model = config('sadeem.model_names.city');

Route::apiResource($table, PublicCityController::class)->only(['index','show']);
