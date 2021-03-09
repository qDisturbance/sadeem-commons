<?php

namespace Sadeem\Commons\Http\Controllers;

use Sadeem\Commons\Models\Country;
use Sadeem\Commons\Http\Resources\CountryCollection;

class CountryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return CountryCollection
   */
  public function index(): CountryCollection
  {
    return new CountryCollection(
      (new Country())
        ->searchAndSort(request())
        ->paginate(globalPaginationSize())
    );
  }
}
