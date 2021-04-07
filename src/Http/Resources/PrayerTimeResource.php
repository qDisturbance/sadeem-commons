<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
      $cityCondition = getIncludeCondition(
        $request->input('include'),
        'city'
      );

      return [
        "day" => $this->day,
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
