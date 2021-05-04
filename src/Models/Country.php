<?php

namespace Sadeem\Commons\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

  public function __construct(array $attributes = [])
  {
    if (config('sadeem.use_dandelion_resources')) {
      $this->setTable('countries');
      $this->timestamps = true;
      $this->setConnection(config('sadeem.resource.connection'));
    } else {
      $this->setTable(config('sadeem.table_names.countries'));
      $this->timestamps = config('sadeem.table_timestamps.countries');
    }

    parent::__construct($attributes);
  }

  public $incrementing = true;

  protected $guarded = ['id'];
  protected $primaryKey = 'id';

  protected $fillable = [
    'iso',
    'iso3',
    'name',
    'en_name',
    'ar_name',
    'num_code',
    'phone_code',
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
      $this::query(),
      $this->getTable(),
      [],
      'name'
    );
  }
}
