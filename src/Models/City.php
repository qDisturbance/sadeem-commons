<?php

namespace Sadeem\Commons\Models;

use Sadeem\Commons\Traits\HasCountry;
use Spatie\Activitylog\Traits\LogsActivity;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class City extends Model
{
  use LogsActivity, PostgisTrait, HasCountry;


  public function __construct(array $attributes = [])
  {
    if (config('sadeem.use_dandelion_resources')) {
      $this->setTable('cities');
      $this->timestamps = true;
      $this->setConnection(config('sadeem.resource.connection'));
    } else {
      $this->setTable(config('sadeem.table_names.cities'));
      $this->timestamps = config('sadeem.table_timestamps.cities');
    }

    parent::__construct($attributes);
  }

  protected $with = ['country'];
  protected $guarded = ['id'];

  protected $fillable = [
    'country_id',
    'name',
    'en_name',
    'is_disabled',
    'location'
  ];

  protected $attributes = ['is_disabled' => false,];

  protected $postgisFields = ['location'];

  protected $postgisTypes = [
    'location' => [
      'geomtype' => 'geography',
      'srid' => 4326
    ]
  ];

  // Model Utilities

  /*
   * Searches and sort based on the request parameters
   *
   * @param $request
   * @return Category|mixed
   */
  public function searchAndSort()
  {
    return searchAndSort(
      $this,
      $this->getTable(),
      [],
      'name'
    );
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
      'cities',
      "name"
    );
  }
}
