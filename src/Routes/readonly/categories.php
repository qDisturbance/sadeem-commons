<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\PublicCategoryController;

$table = config('sadeem.table_names.categories');

Route::apiResource($table, PublicCategoryController::class)->only(['index','show']);
