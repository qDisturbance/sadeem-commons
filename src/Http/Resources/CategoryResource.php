<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'is_disabled' => $this->is_disabled,
      'parent' => $this->getCategoryPath($this->parent_id, $arr = [])
    ];
  }

  /**
   * Recurse through the category parents until a top level category is returned
   * @param $parent_id
   * @param $arr
   */
  protected function getCategoryPath($parent_id, $arr)
  {
    if ($parent_id != null)
    {
      $ctg = $this->where('id', $parent_id)->first();
      array_push($arr, $ctg->name);
      return $this->getCategoryPath($ctg->parent_id, $arr);
    } else {
      return $arr;
    }
  }
}
