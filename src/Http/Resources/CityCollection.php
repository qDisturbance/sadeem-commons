<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CityCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request)
  {
    $tableName = config('sadeem.table_names.cities');
    $publicColumns = Schema::getColumnListing($tableName);

    return [
      'data' => $this->collection,
      'links' => [
        'self' => route("{$tableName}.index"),
      ],
      'meta' => [
        'sort_by' => $publicColumns,
      ]
    ];
  }
}
