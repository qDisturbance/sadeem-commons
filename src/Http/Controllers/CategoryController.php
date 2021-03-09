<?php

namespace Sadeem\Commons\Http\Controllers;

use Sadeem\Commons\Models\Category;
use Sadeem\Commons\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
  public function index()
  {
    return new CategoryCollection(
      (new Category())
        ->searchAndSort(request())
        ->paginate(globalPaginationSize())
    );
  }
}
