<?php

namespace Sadeem\Commons\Http\Controllers;

use Sadeem\Commons\Models\City;
use Sadeem\Commons\Models\PrayerTime;
use Sadeem\Commons\Http\Resources\PrayerTimeResource;

class PublicPrayerTimesController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   */
  public function index()
  {
    $tripoliId = City::where('name', 'طرابلس')->firstOrFail()->id;
    $paginate = request()->input('paginate', globalPaginationSize());
    $cityId = request()->input('city_id', $tripoliId);
    $month = request()->input('month', '01');
    $day = request()->input('day');

    if (!empty($day) && !empty($month)) {
      return PrayerTimeResource::collection(PrayerTime::where('city_id', $cityId)
        ->where('day', "{$day}/{$month}")
        ->paginate($paginate));
    }

    return PrayerTimeResource::collection($prayerTimes = PrayerTime::where('city_id', $cityId)
      ->where('day', "like", "%/{$month}%")
      ->paginate($paginate));
  }
}
