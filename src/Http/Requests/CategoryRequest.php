<?php

namespace Sadeem\Commons\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Sadeem\Commons\Models\Category;

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

    $name = $this->input('name');
    $parent_id = $this->input('parent_id');

    $imgValidations = 'file|mimes:jpg,bmp,png|between:50,2048|dimensions:min_width=100,min_height=200';

    if ($method == "POST") {
      return [
        'name' => [
          'required',
          'min:2',
          'max:255',
          Rule::unique($table)->where(function ($query) use ($name, $parent_id) {
            return $query
              ->where('name', $name)
              ->where('parent_id', $parent_id);
          }),
        ],
        'img' => $imgValidations,
        'parent_id' => "min:3|max:255|exists:{$table}",
      ];
    };
    if ($method == "PATCH") {

      $id = $this->route($model)->id;
      if (empty($parent_id)) $parent_id = Category::where('id', $id)->get()->pluck('parent_id');

      return [
        'name' => [
          'min:2',
          'max:255',
          Rule::unique($table)->where(function ($query) use ($id, $name, $parent_id) {
            return $query
              ->where('name', $name)
              ->where('parent_id', $parent_id)
              ->whereNotIn('id', [$id]);
          }),
        ],
        'img' => $imgValidations,
        'parent_id' => "min:3|max:255|exists:{$table},parent_id,{$parent_id}",
        'is_disabled' => 'boolean'
      ];
    };

  }
}
