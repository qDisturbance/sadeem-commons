<?php


namespace Sadeem\Commons\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Sadeem\Commons\Models\City;
use Sadeem\Commons\Models\Weather;
use Illuminate\Support\Facades\Http;

class UpdateWeather extends Command
{
  protected $signature = 'sadeem:update-weather';

  protected $description = 'update the openweather api data';

  public function handle()
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

      $cityWeather = Weather::where('city_id', $city->id)->first();
      $owData = json_encode($weather['list'][0]);

      if (!$cityWeather)
        Weather::create([
          'city_id' => $city->id,
          'weather' => $owData
        ]);

      if ($cityWeather)
        $cityWeather->update(['weather' => $owData]);
    }

    $timestamp = Carbon::now();
    $this->info("\nUpdating cities weather data at: {$timestamp}\n");
  }

}
