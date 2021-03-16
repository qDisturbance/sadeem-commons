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
    return new CountryCollection(
      (new Country())
        ->searchAndSort()
        ->paginate(globalPaginationSize())
    );
  }
  public function show(Country $country): Response
  {
    $modelResource = new CountryResource($country);
    return modelResponse('GET', __('models.country'), $modelResource);
  }
}
