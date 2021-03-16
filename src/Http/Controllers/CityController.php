<?php

namespace Sadeem\Commons\Http\Controllers;

use Illuminate\Http\Response;
use MStaack\LaravelPostgis\Geometries\Point;
use Sadeem\Commons\Http\Requests\CityRequest;
use Sadeem\Commons\Http\Resources\CityResource;
use Sadeem\Commons\Models\City;
use Sadeem\Commons\Http\Resources\CityCollection;

class CityController extends Controller
{
  public function index()
  {
    return new CityCollection(
      (new City())
        ->searchAndSort()
        ->paginate(globalPaginationSize())
    );
  }

  public function show(City $city): Response
  {
    $modelResource = new CityResource($city);
    return modelResponse('GET', __('models.city'), $modelResource);
  }

  public function store(CityRequest $request): Response
  {
    $city = City::firstOrCreate([
      'name' => $request['name'],
      'is_disabled' => false
    ]);

    if ($request['lat'] && $request['lng']) {
      $city->location = new Point(
        $request['lat'],
        $request['lng']
      );
      $city->save();
    }

    $modelResource = new CityResource($city);

    return modelResponse('POST', __('models.city'), $modelResource);
  }

  public function update(CityRequest $request, City $city): Response
  {
    $data = $request->only(['name', 'is_disabled']);

    $city->update($data);

    if ($request['lat'] && $request['lng']) {
      $city->location = new Point(
        $request['lat'],
        $request['lng']
      );
      $city->save();
    }

    if ($city->wasChanged()) {
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
