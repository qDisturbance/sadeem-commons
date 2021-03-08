<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\CategoryController;

Route::apiResource(config('sadeem.category_table_name'), CategoryController::class);
