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

  /**
   * Searches and sort based on the request parameters
   *
   * @param $request
   */
  public function searchAndSort($request)
  {
    $q = $request->input('q', '');
    $sorts = explode(',', $request->input('sort', ''));

    return $this
      ->when(!empty($q), function () use ($q) {
        return $this->similarity('name', $q);
      })
      ->when(empty($q) && !empty($sorts[0]), function () use ($sorts) {
        return $this->orderQuery($sorts);
      })
      ->when(empty($q) && empty($sorts[0]), function () {
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
}
