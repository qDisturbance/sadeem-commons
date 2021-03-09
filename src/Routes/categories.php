<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\CategoryController;

Route::apiResource(config('sadeem.table_names.categories'), CategoryController::class);
