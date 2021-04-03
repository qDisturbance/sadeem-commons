<?php

namespace Sadeem\Commons\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->check();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
//    $table = config('sadeem.table_names.cities');
//    $model = config('sadeem.model_names.city');
    $countriesTable = config('sadeem.table_names.countries');
//    $countriesModel = config('sadeem.model_names.country');

    $method = $this->method();

    if ($method == "POST") {
      return [
        'name' => "required|min:3|max:255",
        'en_name' => "required|min:3|max:255",
        'lat' => 'string',
        'lng' => 'string',
        'country_id' => "required|int|exists:{$countriesTable},id",
      ];
    };

    if ($method == "PATCH") {

//      $id = $this->route($model)->id;

      return [
        'name' => "min:3|max:255",
        'en_name' => "min:3|max:255",
        'is_disabled' => 'boolean',
        'lat' => 'string',
        'lng' => 'string',
        'country_id' => "int|exists:{$countriesTable},id",
      ];
    };

  }
}
