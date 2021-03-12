<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CountryCollection extends ResourceCollection
{

  /**
   * Transform the resource collection into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request): array
  {
    $tableName = config('sadeem.table_names.countries');
    $publicColumns = Schema::getColumnListing('countries');

    return [
      'data' => $this->collection,
      'links' => [
        'self' => route("{$tableName}.index"),
      ],
      'meta' => [
        'filters' => $publicColumns,
        'icon_sizes' => getFlagIconSizes()
      ]
    ];
  }
}
