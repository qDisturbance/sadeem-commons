<?php

namespace Sadeem\Commons\Models;

use Sadeem\Commons\Traits\HasCountry;
use Spatie\Activitylog\Traits\LogsActivity;
use Sadeem\Commons\Traits\Iso8601Serialization;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class City extends Model
{
  use LogsActivity, PostgisTrait, HasCountry, Iso8601Serialization;


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

  /*
   * -----------------------------------------------------------
   *  Model Attributes
   * -----------------------------------------------------------
   */

  public function getCreatedAtAttribute()
  {
    if (config('sadeem.table_timestamps.cities')) {
      return $this->serializeDate($this->attributes['created_at']);
    } else {
      return [];
    }
  }

  public function getUpdatedAtAttribute()
  {
    if (config('sadeem.table_timestamps.cities')) {
      return $this->serializeDate($this->attributes['updated_at']);
    } else {
      return [];
    }
  }

  /*
   * -----------------------------------------------------------
   *  Model Utilities
   * -----------------------------------------------------------
   */

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
      $this::query(),
      $this->getTable(),
      [],
      'name'
    );
  }
}
