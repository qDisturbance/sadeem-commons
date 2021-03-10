<?php

namespace Sadeem\Commons\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class City extends Model
{
  use LogsActivity, PostgisTrait;


  public function __construct(array $attributes = [])
  {
    $this->setTable(config('sadeem.table_names.cities'));
    $this->timestamps = config('sadeem.table_timestamps.cities');

    parent::__construct($attributes);
  }

  protected $guarded = ['id'];

  protected $fillable = [
    'name',
    'is_disabled',
    'location'
  ];

  protected $attributes = ['is_disabled' => false,];

  protected $postgisFields = ['location',];

  protected $postgisTypes = [
    'location' => [
      'geomtype' => 'geography',
      'srid' => 4326
    ]
  ];

  // Model Utilities

  public function searchAndSort()
  {
    $q = request()->input('q', '');
    $filter = request()->input('filter', '');
    $sorts = explode(',', request()->input('sort', ''));
    $confirmedSort = confirmColumns($sorts, $this->table);

    $arr = buildSearchSortFilterConditions($q, $filter, $confirmedSort);

    return $this
      ->when($arr['qOnly'], function () use ($q) {
        return $this->similarity('name', $q);
      })
      ->when($arr['qFilter'] && request()->filled('filter'), function () use ($q) {
        [$criteria, $value] = $this->confirmFilter();

        return $this
          ->similarity('name', $q)
          ->where($criteria, $value);
      })
      ->when($arr['sortFilter'], function () use ($sorts) {
        [$criteria, $value] = $this->confirmFilter();

        return $this
          ->orderQuery($sorts)
          ->where($criteria, $value);
      })
      ->when($arr['sortOnly'], function () use ($sorts) {
        return $this->orderQuery($sorts);
      })
      ->when($arr['filterOnly'], function () use ($sorts) {
        [$criteria, $value] = $this->confirmFilter();
        return $this->where($criteria, $value);
      })
      ->when($arr['default'], function () {
        return $this;
      });
  }

  public function similarity($column, $q)
  {
    return similarityByColumn($this, $column, $q);
  }

  public function orderQuery($sorts)
  {
    return orderQuery($this, $sorts);
  }

  public function confirmFilter()
  {
    return confirmFilter(
      request('filter'),
      $this->table,
      "name"
    );
  }
}
