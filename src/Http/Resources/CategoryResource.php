<?php

namespace Sadeem\Commons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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

    $thumbImageName = 'thumb_'.basename(Storage::url($this->img));

    return [
      'id' => $this->id,
      'parent_id' => $this->parent_id,
      'name' => $this->name,
      'img' => getDomain() . Storage::url($this->img),
      'thumb' => getDomain() . Storage::url("public/pictures/categories/thumbs/{$thumbImageName}"),
      'is_disabled' => $this->is_disabled,
      'created_at' => $this->when(
        config('sadeem.table_timestamps.categories'),
        $this->created_at
      ),
      'updated_at' => $this->when(
        config('sadeem.table_timestamps.categories'),
        $this->updated_at
      ),
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
