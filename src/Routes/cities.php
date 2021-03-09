<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\CityController;

Route::apiResource(config('sadeem.table_names.cities'), CityController::class);
