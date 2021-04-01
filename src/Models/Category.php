<?php

namespace Sadeem\Commons\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Category extends Model
{
  use LogsActivity;

  public function __construct(array $attributes = [])
  {
    $this->setTable(config('sadeem.table_names.categories'));
    $this->timestamps = config('sadeem.table_timestamps.categories');

    parent::__construct($attributes);
  }

  protected $fillable = [
    'id',
    'name',
    'img',
    'parent_id',
    'is_disabled'
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
        return $this->similarity('name', $q);
      })
      ->when($arr['qFilter'] && request()->filled('filter'), function () use ($q) {
        [$criteria, $value] = $this->confirmFilter();

        if ($criteria == "{$this->getTable()}.parent_id") $value = $this->setToCategoryParentId($value);

        return $this
          ->similarity('name', $q)
          ->where($criteria, $value);
      })
      ->when($arr['sortFilter'], function () use ($sorts) {
        [$criteria, $value] = $this->confirmFilter();

        if ($criteria == "{$this->getTable()}.parent_id") $value = $this->setToCategoryParentId($value);

        return $this
          ->orderQuery($sorts)
          ->where($criteria, $value);
      })
      ->when($arr['sortOnly'], function () use ($sorts) {
        return $this->orderQuery($sorts);
      })
      ->when($arr['filterOnly'], function () use ($sorts) {
        [$criteria, $value] = $this->confirmFilter();

        if ($criteria == "{$this->getTable()}.parent_id") $value = $this->setToCategoryParentId($value);

        return $this->where($criteria, $value);
      })
      ->when($arr['default'], function () {
        return $this;
      });
  }

  public function setToCategoryParentId($value)
  {
    return Category::where('name', $value)->firstOrFail()->id;
  }

  public function getSuperParent($parent_id, $superParent)
  {
    if ($parent_id != null) {
      $superParent = $this->where('id', $parent_id)->first();

      return $this->getSuperParent($superParent->parent_id, $superParent);
    } else {
      return $superParent;
    }
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
