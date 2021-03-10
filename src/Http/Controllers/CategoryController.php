<?php

namespace Sadeem\Commons\Http\Controllers;

use Illuminate\Http\Response;
use Sadeem\Commons\Models\Category;
use Sadeem\Commons\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
  public $modelName = "Category";

  public function index()
  {
    return new CategoryCollection(
      (new Category())
        ->searchAndSort()
        ->paginate(globalPaginationSize())
    );
  }


  public function toggle(Category $category): Response
  {
    isDisabledSwitch($category);

    $modelResource = new CategoryResource($category);

    if ($category->wasChanged()) {
      return modelResponse('PATCH TOGGLE', $this->modelName, $modelResource);
    } else {
      return modelResponse('PATCH TOGGLE FAIL', $this->modelName, $modelResource);
    }
  }

}
