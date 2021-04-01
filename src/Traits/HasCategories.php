<?php

namespace Sadeem\Commons\Traits;

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

  // TODO: add sorting by category in the trait

  /*
  |--------------------------------------------------------------------------
  | Insert Categories
  |--------------------------------------------------------------------------
  |
  | $superParent refers to the top level category, when set null it gets the
  | pictures, events, news categories, otherwise you can access sub
  | categories of those as pictures > portraits
  |
  | by setting topParentName = portraits
  |        and   superParent = pictures
  |
  */
  public function attachCategories($topParentId, $superParent, $categories, $isUpdate): bool
  {
    $changed = false;

    if (isSetArrayInput($categories)) {

      if ($isUpdate) $this->categories()->detach();

      foreach ($categories as $category) {
        $superParentId = $this->getSuperParent($category, $superParent)->id;

        if ($superParentId == $topParentId) $this->categories()->attach($category);
      };

      $changed = true;
    };

    return $changed;
  }

}
