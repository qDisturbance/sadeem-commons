<?php

namespace Sadeem\Commons\Http\Controllers;

use Sadeem\Commons\Models\Category;
use Sadeem\Commons\Http\Resources\CategoryResource;
use Sadeem\Commons\Http\Resources\CategoryCollection;

class PublicCategoryController extends Controller
{
  public function index()
  {
    return new CategoryCollection(
      (new Category())
        ->searchAndSort()
        ->where('is_disabled', false)
        ->paginate(globalPaginationSize())
    );
  }
  public function show(Category $category)
  {
    if (!$category->is_disabled) {
      $modelResource = new CategoryResource($category);
      return modelResponse('GET', __('models.category'), $modelResource);
    }

    return modelResponse('GET FAIL', __('models.category'), null);
  }
}
