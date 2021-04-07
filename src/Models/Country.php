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

  // Model Specific Utilities

  public function searchAndSort()
  {
    $q = request()->input('q', '');
    $filter = request()->input('filter', '');
    $sorts = explode(',', request()->input('sort', ''));
    $confirmedSort = confirmColumns($sorts, $this->table);

    $arr = buildSearchSortFilterConditions($q, $filter, $confirmedSort);

    return $this
      ->when($arr['qOnly'], function () use ($q) {
        return $this->similarity($q);
      })
      ->when($arr['qFilter'] && request()->filled('filter'), function () use ($q) {
        [$criteria, $value] = $this->confirmFilter();

        return $this
          ->similarity($q)
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

  // General Utilities

  public function similarity($q)
  {
    $similarity = similarityByColumn($this, 'en_name', $q);

    $enResults = $similarity->get();
    if (count($enResults) > 0) {
      return $similarity;
    } else {
      return similarityByColumn($this, 'ar_name', $q);
    }
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
      'name'
    );
  }
}
