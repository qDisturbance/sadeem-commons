<?php

namespace Sadeem\Commons\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Category extends Model
{
  use LogsActivity;

  public function __construct(array $attributes = [])
  {
    $tableName = config('sadeem.category_table_name', 'categories');

    if (! isset($this->table)) {
      $this->setTable($tableName);
    }
    if (!isset($this->timestamps)) {
      $this->timestamps = config('sadeem.category_table_timestamps');
    }

    parent::__construct($attributes);
  }
  protected $guarded = ['id'];
  protected $fillable = [
    'name',
    'is_disabled',
    'parent_id',
    'model_name'
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
    $q = $request['q'];
    $sort_by = $request['sort_by'];
    $sort = $request['sort'];
    if ($sort != 'desc') $sort = 'asc';
    if (!isSetNotEmpty($sort_by)) $sort_by = 'name';

    // Conditions list
    $paramQ = isSetNotEmpty($q);
    $paramSortBy = confirmColumn($sort_by, 'categories');

    return $this
      ->when($paramQ, function () use ($q) {
        return $this->similarity($q);
      })
      ->when(!$paramQ && $paramSortBy, function () use ($sort_by, $sort) {
        return $this->orderBy($sort_by, $sort);
      })
      ->when(!$paramQ && !$paramSortBy, function () {
        return $this;
      });
  }

  public function similarity($q)
  {
    return similarityByName($this, $q);
  }
}
