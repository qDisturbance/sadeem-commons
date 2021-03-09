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
   */
  public function searchAndSort($request)
  {

    $q = $request->input('q', '');
    $sorts = explode(',', $request->input('sort', ''));

    return $this
      ->when(!empty($q), function () use ($q) {
        return $this->similarity('name', $q);
      })
      ->when(empty($q) && !empty($sorts[0]), function () use ($sorts) {
        return $this->orderQuery($sorts);
      })
      ->when(empty($q) && empty($sorts[0]), function () {
        return $this;
      });
  }

  public function similarity($column, $q)
  {
    return similarityByColumn($this, $column, $q);
  }

  public function orderQuery($sorts)
  {
    return orderQuery($this, $sorts);
  }
}
