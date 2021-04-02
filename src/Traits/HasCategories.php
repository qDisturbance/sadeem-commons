<?php

namespace Sadeem\Commons\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sadeem\Commons\Models\Category;

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

  // TODO: add sorting by category in the trait

  public function getCategoryIdByName($name)
  {
    return Category::where('name', $name)->firstOrFail()->id;
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
