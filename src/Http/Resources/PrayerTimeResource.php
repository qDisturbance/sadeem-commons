<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Alkoumi\LaravelHijriDate\Hijri;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;


class PrayerTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
      Hijri::setLang('ar');

      $cityCondition = getIncludeCondition(
        $request->input('include'),
        'city'
      );

      $year = Carbon::now()->year;

      $arr = explode('/', $this->day);
      $day = $arr[0];
      $month = $arr[1];
      $date = "{$year}/{$month}/{$day}";

      $hijriDate = Hijri::ShortDate($date);
      $fullHijriDate = Hijri::Date('l ، j F ، Y', $date);

      return [
        "hijri_long" => $fullHijriDate,
        "hijri_short" => $hijriDate,
        "date" => $date,
        "emsak" => $this->emsak,
        "fajer" => $this->fajer,
        "sherook" => $this->sherook,
        "dahor" => $this->dahor,
        "aser" => $this->aser,
        "keroob" => $this->keroob,
        "makrib" => $this->makrib,
        "esha" => $this->esha,
        'city' => $this->when(
          $cityCondition,
          $this->city->makeHidden([
            'location',
            'created_at',
            'updated_at',
            'is_disabled'
          ])
        ),
      ];
    }
}
