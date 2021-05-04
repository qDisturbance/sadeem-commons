<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Sadeem\Commons\Models\Country;

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
    $countryCondition = getIncludeCondition($request->input('include'), 'country');

    $createdAt = $updatedAt = '';
    if (config('sadeem.table_timestamps.categories')) {
      $createdAt = $this->created_at->toIso8601String();
      $updatedAt = $this->updated_at->toIso8601String();
    }

    return [
      'id' => $this->id,
      'country_id' => $this->when(
        !$countryCondition,
        (int)$this->country_id
      ),
      'country' => $this->when(
        $countryCondition,
        new CountryResource(Country::where('id', $this->country_id)->first())
      ),
      'name' => $this->name,
      'en_name' => $this->en_name,
      'is_disabled' => $this->is_disabled,
      'created_at' => $this->when(
        config('sadeem.table_timestamps.cities'),
        $createdAt
      ),
      'updated_at' => $this->when(
        config('sadeem.table_timestamps.cities'),
        $updatedAt
      ),
      'location' => $this->location
    ];
  }
}
