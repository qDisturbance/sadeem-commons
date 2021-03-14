<?php

namespace Sadeem\Commons\Http\Controllers;

use Illuminate\Http\Response;
use Sadeem\Commons\Models\City;
use Sadeem\Commons\Http\Resources\CityResource;
use Sadeem\Commons\Http\Resources\CityCollection;

class PublicCityController extends Controller
{
  public $modelName = "City";

  public function index()
  {
    return new CityCollection(
      (new City())
        ->searchAndSort()
        ->where('is_disabled', false)
        ->paginate(globalPaginationSize())
    );
  }

  public function show(City $city): Response
  {
    if (!$city->is_disabled) {
      $modelResource = new CityResource($city);
      return modelResponse('GET', $this->modelName, $modelResource);
    }

    return modelResponse('GET FAIL', $this->modelName, null);
  }
}
