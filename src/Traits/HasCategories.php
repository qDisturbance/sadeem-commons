<?php

namespace Sadeem\Commons\Traits;

use Sadeem\Commons\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasCategories
{
  public function categories(): BelongsToMany
  {
    return $this->morphToMany(
      config('sadeem.models.category'),
      'model',
      config('sadeem.table_names.model_has_categories'),
      config('sadeem.column_names.model_morph_key'),
      'category_id'
    );
  }

  public function getCategoryIdByName($name)
  {
    return Category::where('name', $name)->firstOrFail()->id;
  }

  public function getCategoryById($id)
  {
    return Category::where('id', $id)->firstOrFail()->id;
  }

  public function orderByCategory($query, $sortDirection)
  {

    $table = config('sadeem.table_names.categories');
    $morphTable = config('sadeem.table_names.model_has_categories');
    $morphCol = config('sadeem.column_names.model_morph_key');

    $query
      ->join(
        $morphTable,
        "{$morphTable}.{$morphCol}",
        '=',
        $this->getTable() . ".id"
      )
      ->join(
        $table,
        "{$table}.id",
        '=',
        "{$morphTable}.category_id"
      )
      ->select(
        $this->getTable() . ".*",
        "{$table}.name as category_name"
      )
      ->orderBy("category_name", $sortDirection);

    return $query;
  }

  public function filterByCategory($query, $id)
  {
    $query->whereHas('categories', function (Builder $query) use ($id) {
      $query->where('id', $id);
    });

    return $query;
  }

  /*
  |--------------------------------------------------------------------------
  | Attach Categories
  |--------------------------------------------------------------------------
  |
  | $superParentID accepts categories that are direct children to it
  |
  */
  public function attachCategories($superParentId, $categories, $isUpdate): bool
  {
    $changed = false;

    if (isSetArrayInput($categories)) {

      if ($isUpdate) {

        $diff = array_diff($categories, $this->categories()->pluck('id')->toArray());

        if (empty($diff)) return false;

        $this->categories()->detach();
      }

      foreach ($categories as $categoryId) {
        $category = Category::where('id', $categoryId)->firstOrFail();

        if ($category->parent_id == $superParentId)
          $this->categories()->attach($category);
      };

      $changed = true;
    };

    return $changed;
  }
}
