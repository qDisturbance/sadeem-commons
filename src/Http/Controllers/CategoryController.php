<?php

namespace Sadeem\Commons\Http\Controllers;

use Sadeem\Commons\Models\Category;

class CategoryController extends Controller
{
  public function index()
  {
    return Category::all();
  }
}
