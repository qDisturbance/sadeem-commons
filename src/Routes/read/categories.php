<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\CategoryController;

$table = config('sadeem.table_names.categories');
$model = config('sadeem.model_names.category');

Route::apiResource($table, CategoryController::class)->only(['index','show']);
