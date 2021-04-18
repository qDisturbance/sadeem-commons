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
    'img',
    'parent_id',
    'is_disabled'
  ];

  // Model Utilities

  /**
   * Recurse through the category parents until a top level category is returned
   * @param $parent_id
   * @param $arr
   * @return array
   */
  public function getCategoryPathNames($parent_id, $arr): array
  {
    if ($parent_id != null) {
      $ctg = $this->where('id', $parent_id)->first();
      array_push($arr, $ctg->name);
      return $this->getCategoryPathNames($ctg->parent_id, $arr);
    } else {
      return $arr;
    }
  }

  /**
   * Recurse through the category parents until a top level category is returned
   * @param $parent_id
   * @param $arr
   * @return array
   */
  public function getCategoryPathIds($parent_id, $arr): array
  {
    if ($parent_id != null) {
      $ctg = $this->where('id', $parent_id)->first();
      array_push($arr, $ctg->id);
      return $this->getCategoryPathIds($ctg->parent_id, $arr);
    } else {
      return $arr;
    }
  }

  public function getSuperParent($parent_id, $superParent)
  {
    if ($parent_id != null) {
      $superParent = $this->where('id', $parent_id)->first();

      return $this->getSuperParent($superParent->parent_id, $superParent);
    } else {
      return $superParent;
    }
  }

  /*
   * Searches and sort based on the request parameters
   *
   */
  public function searchAndSort()
  {
    return searchAndSort(
      $this,
      $this->getTable(),
      [],
      'name'
    );
  }

  public function similarity($column, $q)
  {
    return similarityByColumn($this, $column, $q);
  }

  public function orderQuery($sorts)
  {
    return orderQuery($this, $sorts);
  }

  public function confirmFilter()
  {
    return confirmFilter(
      request('filter'),
      'categories',
      "name"
    );
  }
}
