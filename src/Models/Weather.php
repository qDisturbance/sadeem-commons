<?php

namespace Sadeem\Commons\Models;

use Sadeem\Commons\Traits\HasCity;
use Sadeem\Commons\Traits\Iso8601Serialization;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Weather extends Model
{
  use HasCity, Iso8601Serialization;

  public function __construct(array $attributes = [])
  {
    if (config('sadeem.use_dandelion_resources')) {
      $this->setTable('weather');
      $this->setConnection(config('sadeem.resource.connection'));
    } else {
      $this->setTable(config('sadeem.table_names.weather'));
    }

    parent::__construct($attributes);
  }

  public $keyType = 'uuid';
  public $incrementing = false;
  public $primaryKey = 'city_id';
  public $timestamps = true;

  protected $with = ['city'];

  protected $fillable = [
    'city_id',
    'weather'
  ];

  /*
   * -----------------------------------------------------------
   *  Model Attributes
   * -----------------------------------------------------------
   */
  public function getCreatedAtAttribute(): ?string
  {
    return $this->serializeDate($this->attributes['created_at']);
  }

  public function getUpdatedAtAttribute(): ?string
  {
    return $this->serializeDate($this->attributes['updated_at']);
  }
}
