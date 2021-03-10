<?php

namespace Sadeem\Commons\Http\Controllers;

use Sadeem\Commons\Models\City;
use Sadeem\Commons\Http\Resources\CityCollection;

class CityController extends Controller
{
  public $modelName = "City";

  public function index()
  {
    return new CityCollection(
      (new City())
        ->searchAndSort()
        ->paginate(globalPaginationSize())
    );
  }
}
