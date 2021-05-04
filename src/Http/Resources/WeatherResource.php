<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request)
  {
    $createdAt = $updatedAt = '';
    if (config('sadeem.table_timestamps.categories')) {
      $createdAt = $this->created_at->toIso8601String();
      $updatedAt = $this->updated_at->toIso8601String();
    }

    return [
      'city' => $this->city->makeHidden([
        'is_disabled',
        'created_at',
        'updated_at'
      ]),
      'created_at' => $createdAt,
      'updated_at' => $updatedAt,
      'weather' => json_decode($this->weather),
    ];
  }
}
