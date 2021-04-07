<?php

use Illuminate\Support\Facades\Route;
use Sadeem\Commons\Http\Controllers\PublicPrayerTimesController;

$table = config('sadeem.table_names.prayer_times');

$table = str_replace('_', '-',$table);

Route::apiResource($table, PublicPrayerTimesController::class)->only(['index']);
