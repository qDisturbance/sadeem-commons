<?php

namespace Sadeem\Commons\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

  public function __construct(array $attributes = [])
  {
    $this->setTable(config('sadeem.table_names.countries'));
    $this->timestamps = config('sadeem.table_timestamps.countries');

    parent::__construct($attributes);
  }

  protected $guarded = ['id'];
  protected $primaryKey = 'id';
  public $incrementing = true;

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

  public function searchAndSort($request)
  {
    $q = $request->input('q', '');
    $sorts = explode(',', $request->input('sort', ''));
    $confirmed = confirmColumns($sorts, config('sadeem.table_names.countries'));

    return $this
      ->when(!$confirmed && !empty($q), function () use ($q) {
        $similarityByName = $this->similarityByName('name', $q);

        $enResults = $similarityByName->get();

        if (count($enResults) > 0) {
          return $similarityByName;
        } else {
          return $this->similarityByName('ar_name', $q);
        }
      })
      ->when($confirmed && empty($q) && !empty($sorts[0]), function () use ($sorts) {
        return $this->orderQuery($sorts);
      })
      ->when(!$confirmed && empty($q) && empty($sorts[0]), function () {
        return $this;
      });
  }

  public function similarityByName($column, $q)
  {
    return similarityByColumn($this, $column, $q);
  }

  public function orderQuery($sorts)
  {
    return orderQuery($this, $sorts);
  }
}
