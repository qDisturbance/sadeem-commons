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

  /**
   * Searches and sort based on the request parameters
   *
   * @param $request
   * @return Country|mixed
   */
  public function searchAndSort($request)
  {
    // Params list
    $q = $request['q'];
    $sort_by = $request['sort_by'];
    $sort = $request['sort'];
    if ($sort != 'desc') $sort = 'asc';

    // Conditions list
    $paramQ = isSetNotEmpty($q);
    $paramSortBy = confirmColumn($sort_by, 'countries');

    return $this
      ->when($paramQ, function () use ($q) {

        $similarityByName = $this->similarityByName($q);

        $enResults = $similarityByName->get();

        if(count($enResults) > 0)
        {
          return $similarityByName;
        } else {
          return $this->similarityByArName($q);
        }
      })
      ->when(!$paramQ && $paramSortBy, function () use ($sort_by, $sort) {
        return $this->orderBy($sort_by, $sort);
      })
      ->when(!$paramQ && !$paramSortBy, function () {
        return $this;
      });
  }

  public function similarityByName($q)
  {
    return $this->selectRaw("*, similarity(name, ?) as difference", ["{$q}"])
      ->whereRaw('similarity(name, ?) > ?', ["{$q}", 0.1])
      ->orderBy('difference', 'desc');
  }
  public function similarityByArName($q)
  {
    return $this->selectRaw("*, similarity(ar_name, ?) as difference", ["{$q}"])
      ->whereRaw('similarity(ar_name, ?) > ?', ["{$q}", 0.1])
      ->orderBy('difference', 'desc');
  }
}
