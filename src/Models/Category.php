<?php

namespace Sadeem\Commons\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Category extends Model
{
  use LogsActivity;

  public function __construct(array $attributes = [])
  {
    $this->setTable(config('sadeem.table_names.categories'));
    $this->timestamps = config('sadeem.table_timestamps.categories');

    parent::__construct($attributes);
  }

  protected $fillable = [
    'id',
    'name',
    'parent_id',
    'is_disabled'
  ];

  // Model Utilities

  /**
   * Searches and sort based on the request parameters
   *
   * @param $request
   * @return Category|mixed
   */
  public function searchAndSort($request)
  {
    // Params list
    $q = $request->only('q');

    $sort = $request->only('sort');
    $sort_by = $request->only('sort_by');

    if ($sort != 'desc') $sort = 'asc';
    if (!$sort_by) $sort_by = 'name';

    $filter = $request->only('filter');

    // Conditions list
    $paramQ = $q;
    $paramSortBy = confirmColumn($sort_by, config('sadeem.table_names.categories'));

    return $this
      ->when($paramQ, function () use ($sort_by, $q) {
        return $this->similarity($sort_by, $q);
      })
      ->when(!$paramQ && $paramSortBy, function () use ($sort_by, $sort) {
        return $this->orderBy($sort_by, $sort);
      })
      ->when(!$paramQ && !$paramSortBy, function () {
        return $this;
      });
  }

  public function similarity($column, $q)
  {
    return similarityByColumn($this, $column, $q);
  }
}
