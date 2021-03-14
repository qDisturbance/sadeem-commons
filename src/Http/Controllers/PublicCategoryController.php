<?php

namespace Sadeem\Commons\Http\Controllers;

use Sadeem\Commons\Models\Category;
use Sadeem\Commons\Http\Resources\CategoryResource;
use Sadeem\Commons\Http\Resources\CategoryCollection;

class PublicCategoryController extends Controller
{
  public $modelName = "Category";

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
      return modelResponse('GET', $this->modelName, $modelResource);
    }

    return modelResponse('GET FAIL', $this->modelName, null);
  }
}
