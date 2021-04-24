<?php

namespace Sadeem\Commons\Http\Controllers;

use Illuminate\Http\Response;
use Sadeem\Commons\Models\Category;
use Sadeem\Commons\Http\Resources\CategoryResource;
use Sadeem\Commons\Http\Resources\CategoryCollection;

class PublicCategoryController extends Controller
{
  public function index(): CategoryCollection
  {
    $categories = (new Category())
      ->searchAndSort()
      ->where('is_disabled', false);

    if (!empty(request()->input('leaves'))) {
      $leaves = (new Category)->getLeaves(request()->input('leaves'), []);
      $categories = $categories->whereIn('id', $leaves);
    }

    if (!empty(request()->input('paginate'))) {
      $categories = $categories
        ->paginate(request()->input('paginate', globalPaginationSize()));
    } else {
      $categories = $categories->get();
    }

    return new CategoryCollection($categories);
  }

  public function show(Category $category): Response
  {
    if (!$category->is_disabled) {
      $modelResource = new CategoryResource($category);
      return modelResponse('GET', __('models.category'), $modelResource);
    }

    return modelResponse('GET FAIL', __('models.category'), null);
  }
}
