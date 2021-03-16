<?php

namespace Sadeem\Commons\Http\Controllers;

use Illuminate\Http\Response;
use Sadeem\Commons\Models\Category;
use Sadeem\Commons\Http\Requests\CategoryRequest;
use Sadeem\Commons\Http\Resources\CategoryResource;
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

  public function show(Category $category)
  {
    $modelResource = new CategoryResource($category);
    return modelResponse('GET', __('models.category'), $modelResource);
  }

  public function store(CategoryRequest $request): Response
  {
    $parent = null;

    if(!empty($request['parent']))
      $parent = Category::where('name', $request['parent'])->firstOrFail()->id;

    $category = Category::firstOrCreate([
      'name' => $request['name'],
      'parent_id' => $parent,
      'is_disabled' => false
    ]);

    $modelResource = new CategoryResource($category);

    return modelResponse('POST', __('models.category'), $modelResource);
  }

  public function update(CategoryRequest $request, Category $category)
  {
    $data = $request->only(['name', 'is_disabled']);

    if(!empty($request['parent']))
      $data['parent_id']  = Category::where('name', $request['parent'])->firstOrFail()->id;

    $category->update($data);

    if ($category->wasChanged()) {
      $modelResource = new CategoryResource($category);
      return modelResponse('PATCH', __('models.category'), $modelResource);
    } else {
      return modelResponse('PATCH FAIL', __('models.category'), null);
    }
  }

  public function toggle(Category $category): Response
  {
    isDisabledSwitch($category);

    $modelResource = new CategoryResource($category);

    if ($category->wasChanged()) {
      return modelResponse('PATCH TOGGLE', __('models.category'), $modelResource);
    } else {
      return modelResponse('PATCH TOGGLE FAIL', __('models.category'), $modelResource);
    }
  }

  public function destroy(Category $category)
  {
    $modelResource = new CategoryResource($category);
    $category->delete();

    return modelResponse('DELETE', __('models.category'), $modelResource);
  }
}
