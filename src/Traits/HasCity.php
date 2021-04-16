<?php

namespace Sadeem\Commons\Traits;

use Sadeem\Commons\Models\City;
use Illuminate\Database\Eloquent\Builder;
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

  public function getCityIdByName($name)
  {
    return City::where('name', $name)->firstOrFail()->id;
  }

  public function orderByCity($query, $sortDirection)
  {
    $table = config('sadeem.table_names.cities');
    $col = config('sadeem.column_names.city_id');
    $query
      ->join($table,
        $this->getTable() . ".{$col}",
        "=", "{$table}.id")
      ->select("{$this->getTable()}.*")
      ->orderBy("{$table}.name", $sortDirection);

    return $query;
  }

  public function filterByCity($query, $id)
  {
    $query->whereHas('city', function (Builder $query) use ($id) {
      $query->where('id', $id);
    });

    return $query;
  }
}
