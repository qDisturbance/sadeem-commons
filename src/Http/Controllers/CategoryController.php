<?php

namespace Sadeem\Commons\Http\Controllers;

use Illuminate\Http\Response;
use Sadeem\Commons\Models\Category;
use Sadeem\Commons\Http\Requests\CategoryRequest;
use Sadeem\Commons\Http\Resources\CategoryResource;
use Sadeem\Commons\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
  public function index(): CategoryCollection
  {
    if (!empty(request()->input('paginate'))) {
      return new CategoryCollection(
        (new Category())
          ->searchAndSort()
          ->paginate(request()->input('paginate', globalPaginationSize()))
      );

    } else {
      return new CategoryCollection(
        (new Category())
          ->searchAndSort()
          ->get()
      );
    }
  }

  public function show(Category $category): Response
  {
    $modelResource = new CategoryResource($category);
    return modelResponse('GET', __('models.category'), $modelResource);
  }

  public function store(CategoryRequest $request): Response
  {
    $parentId = null;

    if (!empty($request->input('parent_id')))
      $parentId = Category::where('id', $request->input('parent_id'))->firstOrFail()->id;

    $name = $request->input('name');
    $img = insertImage($request->file('img'), 'categories');

    $category = Category::firstOrCreate([
      'name' => $name,
      'img' => $img,
      'is_disabled' => false,
      'parent_id' => $parentId
    ]);

    if ($category) {
      $modelResource = new CategoryResource($category);
      return modelResponse('POST', __('models.category'), $modelResource);
    } else {
      return modelResponse('POST FAIL', __('models.category'), null);
    }
  }

  /*
   * Update an image alone
   */
  public function updateImage(CategoryRequest $request, Category $category): Response
  {
    return updateImage(
      $category,
      __('models.category'),
      'categories',
      $request->file('img')
    );
  }

  public function update(CategoryRequest $request, Category $category): Response
  {
    $data = $request->only(['name', 'is_disabled']);

    if (!empty($request->input('parent_id'))) {
      $data['parent_id']= Category::where('id', $request->input('parent_id'))->firstOrFail()->id;
    }

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

  public function destroy(Category $category): Response
  {
    $modelResource = new CategoryResource($category);
    $category->delete();

    return modelResponse('DELETE', __('models.category'), $modelResource);
  }
}
