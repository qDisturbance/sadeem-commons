<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\CountryController;

Route::apiResource(config('sadeem.table_names.countries'), CountryController::class);
