<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Schema;

class CategoryCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request)
  {
    $tableName = config('sadeem.table_names.categories');
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
