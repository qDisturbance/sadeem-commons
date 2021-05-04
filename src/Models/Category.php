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
   * returns array of 2nd level sub category ids
   */
  public function getLeaves($id, $arr)
  {
    $categories = Category::where('parent_id', $id)->pluck('id');

    foreach ($categories as $category) {
      $subCategories = Category::where('parent_id', $category)->pluck('id');
      foreach ($subCategories as $subCategory) {
        array_push($arr, $subCategory);
      }
    }
    return $arr;
  }

  /*
   * Searches and sort based on the request parameters
   *
   */
  public function searchAndSort()
  {
    return searchAndSort(
      $this,
      $this::query(),
      $this->getTable(),
      [],
      'name'
    );
  }
}
