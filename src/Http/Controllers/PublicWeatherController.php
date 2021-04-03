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

  public function store(): Response
  {

    $cities = City::all();

    foreach ($cities as $city) {
      $lat = $city->location->getLat();
      $lng = $city->location->getLng();

      $weather = Http::get("api.openweathermap.org/data/2.5/find", [
        'lat' => $lat,
        'lon' => $lng,
        'cnt' => 1,
        'units' => 'metric',
        'lang' => 'ar',
        'appid' => config('openweather.api_key')
      ])->json();

      Weather::create([
        'city_id' => $city->id,
        'weather' => json_encode($weather['list'][0])
      ]);

    }
    return response([
      'msg' => 'Inserted cities weather data'
    ]);
  }
}
