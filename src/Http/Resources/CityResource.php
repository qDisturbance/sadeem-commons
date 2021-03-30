<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'country_id' => $this->country_id,
      'name' => $this->name,
      'en_name' => $this->en_name,
      'is_disabled' => $this->is_disabled,
      'location' => $this->location
    ];
  }
}
