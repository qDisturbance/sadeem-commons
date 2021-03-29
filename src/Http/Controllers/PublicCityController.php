<?php

namespace Sadeem\Commons\Http\Controllers;

use Illuminate\Http\Response;
use Sadeem\Commons\Models\City;
use Sadeem\Commons\Http\Resources\CityResource;
use Sadeem\Commons\Http\Resources\CityCollection;

class PublicCityController extends Controller
{
  public function index()
  {
    if (!empty(request()->input('paginate'))) {
      return new CityCollection(
        (new City())
          ->searchAndSort()
          ->where('is_disabled', false)
          ->paginate(request()->input('paginate', globalPaginationSize()))
      );

    } else {
      return new CityCollection(
        (new City())
          ->searchAndSort()
          ->where('is_disabled', false)
          ->get()
      );
    }
  }

  public function show(City $city): Response
  {
    if (!$city->is_disabled) {
      $modelResource = new CityResource($city);
      return modelResponse('GET', __('models.city'), $modelResource);
    }

    return modelResponse('GET FAIL', __('models.city'), null);
  }
}
