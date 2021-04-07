<?php

namespace Sadeem\Commons\Http\Controllers;

use Illuminate\Http\Response;
use Sadeem\Commons\Http\Resources\WeatherResource;
use Sadeem\Commons\Models\City;
use Sadeem\Commons\Models\Weather;
use Illuminate\Support\Facades\Http;

class PublicWeatherController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   */
  public function index()
  {
    $city_id = request()->input('city_id');
    $paginate = request()->input('paginate');

    if (!empty($city_id)) {
      return new WeatherResource (Weather::where('city_id', $city_id)->firstOrFail());
    } else if(empty($city_id) && !empty($paginate)) {
      return WeatherResource::collection(Weather::paginate($paginate));
    } else {
      return WeatherResource::collection(Weather::all());
    }
  }
}
