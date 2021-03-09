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
}
