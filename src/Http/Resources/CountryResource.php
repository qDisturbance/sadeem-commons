<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   */
  public function toArray($request)
  {
    $iconSize = $request->input('iconSize', '');

    if ($iconSize) {
      $availableSize = in_array($iconSize, getFlagIconSizes());
      if ($availableSize) $iconSize = $request['iconSize'];
    } else {
      $iconSize = 64;
    }

    $iconCondition = getIncludeCondition(
      $request->get('include'),
      'icon'
    );

    $iconFileName = strtolower($this->iso).'.png';

    return [
      'id' => $this->id,
      'iso' => $this->iso,
      'name' => $this->name,
      'en_name' => $this->en_name,
      'ar_name' => $this->ar_name,
      'iso3' => $this->iso3,
      'num_code' => $this->num_code,
      'phone_code' => $this->phone_code,
      'icon' => $this->when(
        $iconCondition,
        storage_path(("app/public/assets/flags/{$iconSize}x{$iconSize}/{$iconFileName}"))
      ),
    ];
  }
}
