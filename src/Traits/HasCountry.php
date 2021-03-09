<?php

namespace Sadeem\Commons\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasCountry
{
  public function country(): HasOne
  {
    return $this->hasOne(
      config('sadeem.models.country'),
      'id',
      config('sadeem.column_names.country_id'));
  }
}
