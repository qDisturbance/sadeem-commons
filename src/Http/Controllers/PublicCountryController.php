<?php

namespace Sadeem\Commons\Http\Controllers;

use Illuminate\Http\Response;
use Sadeem\Commons\Models\Country;
use Sadeem\Commons\Http\Resources\CountryResource;
use Sadeem\Commons\Http\Resources\CountryCollection;

class PublicCountryController extends Controller
{
  public function index(): CountryCollection
  {
    if (!empty(request()->input('paginate'))) {
      return new CountryCollection(
        (new Country())
          ->searchAndSort()
          ->paginate(request()->input('paginate', globalPaginationSize()))
      );

    } else {
      return new CountryCollection(
        (new Country())
          ->searchAndSort()
          ->get()
      );
    }
  }
  public function show(Country $country): Response
  {
    $modelResource = new CountryResource($country);
    return modelResponse('GET', __('models.country'), $modelResource);
  }
}
