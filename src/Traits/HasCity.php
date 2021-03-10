<?php

namespace Sadeem\Commons\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasCity
{
  public function city(): HasOne
  {
    return $this->hasOne(
      config('sadeem.models.city'),
      'id',
      config('sadeem.column_names.city_id'));
  }
}
