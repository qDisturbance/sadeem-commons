<?php

namespace Sadeem\Commons\Http\Controllers;

use Illuminate\Http\Response;
use Sadeem\Commons\Http\Requests\CityRequest;
use Sadeem\Commons\Http\Resources\CityResource;
use Sadeem\Commons\Models\City;
use Sadeem\Commons\Http\Resources\CityCollection;

class CityController extends Controller
{
  public function index()
  {
    if (!empty(request()->input('paginate'))) {
      return new CityCollection(
        (new City())
          ->searchAndSort()
          ->paginate(request()->input('paginate', globalPaginationSize()))
      );

    } else {
      return new CityCollection(
        (new City())
          ->searchAndSort()
          ->get()
      );
    }
  }

  public function show(City $city): Response
  {
    $modelResource = new CityResource($city);
    return modelResponse('GET', __('models.city'), $modelResource);
  }

  public function store(CityRequest $request): Response
  {
    $data = $request->only(['name','en_name','country_id']);
    $data['is_disabled'] = false;

    $city = City::firstOrCreate($data);

    updateLocationAttribute(
      $city,
      $request->input('lat'),
      $request->input('lng')
    );

    $modelResource = new CityResource($city);

    return modelResponse('POST', __('models.city'), $modelResource);
  }

  public function update(CityRequest $request, City $city): Response
  {
    $data = $request->only(['name','en_name', 'is_disabled','country_id']);

    $city->update($data);

    $locationChanged =
      updateLocationAttribute(
        $city,
        $request->input('lat'),
        $request->input('lng')
      );

    if ($city->wasChanged() || $locationChanged) {
      $modelResource = new CityResource($city);
      return modelResponse('PATCH', __('models.city'), $modelResource);
    } else {
      return modelResponse('PATCH FAIL', __('models.city'), null);
    }
  }

  public function toggle(City $city): Response
  {
    isDisabledSwitch($city);

    $modelResource = new CityResource($city);

    if ($city->wasChanged()) {
      return modelResponse('PATCH TOGGLE', __('models.city'), $modelResource);
    } else {
      return modelResponse('PATCH TOGGLE FAIL', __('models.city'), $modelResource);
    }
  }

  public function destroy(City $city): Response
  {
    $modelResource = new CityResource($city);
    $city->delete();

    return modelResponse('DELETE', __('models.city'), $modelResource);
  }
}
