<?php

namespace Sadeem\Commons\Models;

use Illuminate\Database\Eloquent\Builder;
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
    $q = $request->input('q', '');
    $sorts = explode(',', $request->input('sort', ''));

    return $this
      ->when(!empty($q), function () use ($q) {
        return $this->similarity('name', $q);
      })
      ->when(empty($q) && !empty($sorts[0]), function () use ($sorts) {
        return $this->orderQuery($sorts);
      })
      ->when(empty($q) && empty($sorts), function () {
        return $this;
      });
  }

  /**
   * @param $column
   * @param $q
   * @return mixed
   */
  public function similarity($column, $q)
  {
    return similarityByColumn($this, $column, $q);
  }

  /**
   * Builds a query using multiple sort param values
   * as in ?sort=parent_id, -is_disabled
   *
   * @param $sorts
   * @return Builder
   */
  public function orderQuery($sorts): Builder
  {
    $query = $this::query();

    foreach ($sorts as $sortColumn) {

      // remove empty spaces
      $sortColumn = trim($sortColumn);

      // decide the order direction
      $sortDirection = str_starts_with($sortColumn, '-') ? 'desc' : 'asc';

      // trim the order direction
      $sortColumn = ltrim($sortColumn, '-');

      $query->orderBy($sortColumn, $sortDirection);
    }

    return $query;
  }
}
