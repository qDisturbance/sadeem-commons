<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\PublicCategoryController;

$table = config('sadeem.table_names.categories');
$model = config('sadeem.model_names.category');

Route::apiResource($table, PublicCategoryController::class)->only(['index','show']);
