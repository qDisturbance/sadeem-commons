<?php

namespace Sadeem\Commons\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->check() && !auth()->user()->is_disabled;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $table = config('sadeem.table_names.categories');
    $model = config('sadeem.model_names.category');

    $method = $this->method();

    if ($method == "POST") {
      return [
        'name' => 'required|min:3|max:255',
        'parent_id' => "min:3|max:255|exists:{$table}",
      ];
    };
    if ($method == "PATCH") {

      $id = $this->route($model)->id;
      return [
        'name' => "min:3|max:255",
        'parent_id' => "min:3|max:255|exists:{$table},parent_id,{$id}",
        'is_disabled' => 'boolean'
      ];
    };

  }
}
