<?php

use Sadeem\Commons\Traits\HasCategories;
use Sadeem\Commons\Traits\HasCity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use MStaack\LaravelPostgis\Geometries\Point;

/**
 * Match the sortBy string to a column in a table.
 * 3rd arg takes singular relation name city or
 * category to confirm it for sort
 *
 * @param array $sorts
 * @param string $tableName
 * @param array $relationColumns
 * @return bool
 */
function confirmColumns($sorts, $tableName, $relationColumns = []): bool
{
  if (count($sorts) <= 0) return false;

  $columns = Schema::getColumnListing($tableName);

  foreach ($relationColumns as $relation)
    array_push($columns, $relation);

  $confirmed = true;

  foreach ($sorts as $sort) {

    $sort = str_replace('-', '', $sort);
    $sort = str_replace(' ', '', $sort);

    $inColumns = in_array($sort, $columns);
    $confirmed = $confirmed && $inColumns;
  }
  return $confirmed;
}

/**
 * @param $filter string
 * @param $tableName string
 * @param $default string
 * @return array
 */
function confirmFilter($filter, $tableName, $default): array
{
  if (!strpos($filter, ':')) return [$default, ''];

  [$criteria, $value] = explode(':', $filter);

  $exists = confirmColumns([$criteria], $tableName);

  if ($exists) return [$tableName . '.' . $criteria, $value];
  if ($criteria == 'role') return [$criteria, $value];
  if ($criteria == 'city') return [$criteria, $value];
  if ($criteria == 'category') return [$criteria, $value];

  return [$tableName . '.' . $default, $value];
}

/**
 * Match the sortBy string to a column in a table.
 *
 * @param $modelInstance
 * @return void
 */
function isDisabledSwitch($modelInstance): void
{
  $isDisabled = $modelInstance->is_disabled;
  $modelInstance->is_disabled = !$isDisabled;
  $modelInstance->save();
}

/**
 * Similarity By Column string search for all models.
 *
 * @param $modelInstance
 * @param $column
 * @param $q
 *
 */
function similarityByColumn($modelInstance, $column, $q)
{
  $difference = "similarity({$column}, ?)";

  return $modelInstance
    ->selectRaw("*, {$difference} as difference", ["{$q}"])
    ->whereRaw("{$difference} > ?", ["{$q}", 0.1])
    ->orderBy('difference', 'desc');
}

/**
 * Builds a query using multiple sort param values
 * as in ?sort=parent_id, -is_disabled
 *
 * @param $modelInstance
 * @param $sorts
 * @return Builder
 */
function orderQuery($modelInstance, $sorts): Builder
{

  $class = get_class($modelInstance);
  $traits = class_uses($class);

  $query = $modelInstance::query();

  foreach ($sorts as $sortColumn) {
    // remove empty spaces
    $sortColumn = str_replace(' ', '', $sortColumn);

    // decide the order direction
    $sortDirection = str_starts_with($sortColumn, '-') ? 'desc' : 'asc';

    // trim the order direction
    $sortColumn = str_replace('-', '', $sortColumn);

    if ($sortColumn == 'city_id' || $sortColumn == 'city') {

      // sort by city morph relation in the trait
      $modelInstance->orderByCity($query, $sortDirection);

    } elseif ($sortColumn == 'category_id' || $sortColumn == 'category') {

      // sort by category morph relation in the trait
      $modelInstance->orderByCategory($query, $sortDirection);

    } elseif ($sortColumn == 'role') {
      // sort by role morph relation in the trait
      $modelInstance->orderByRole($query, $sortDirection);

    } else {
      // sort by table column
      $query->orderBy($sortColumn, $sortDirection);
    }

  }

  return $query;
}

/**
 * Takes the search, sort and filter params
 * and returns an array of combo conditions
 *
 * @param string $q
 * @param $filter
 * @param $confirmedSort
 * @return array
 */
function buildSearchSortFilterConditions($q, $filter, $confirmedSort): array
{
  $arr['qOnly'] = !empty($q) && empty($filter);
  $arr['qFilter'] = !empty($q) && !empty($filter);
  $arr['sortFilter'] = empty($q) && !empty($filter) && $confirmedSort;
  $arr['sortOnly'] = empty($q) && empty($filter) && $confirmedSort;
  $arr['filterOnly'] = empty($q) && !empty($filter) && !$confirmedSort;
  $arr['default'] = empty($q) && empty($filter) && !$confirmedSort;

  return $arr;
}

/*
|--------------------------------------------------------------------------
| update location attribute
|--------------------------------------------------------------------------
|
| checks value change of Point Type attribute, updates it
| and returns a boolean
|
*/
function updateLocationAttribute($moduleInstance, $lat, $lng): bool
{
  $changed = false;
  $oldLoc = $moduleInstance->location;

  if (!is_null($moduleInstance->location)) {
    if ($oldLoc->getLat() == $lat && $oldLoc->getLng() == $lng)
      return false;
  }

  if (!empty($lat) && !empty($lng)) {
    $moduleInstance->location = new Point($lat, $lng);
    $moduleInstance->save();
    $changed = true;
  }
  return $changed;
}

/*
|--------------------------------------------------------------------------
| Search and Sort
|--------------------------------------------------------------------------
|
| the application search and sort handler
| this function is fit to be used inside
| any model that defines it in its class
|
*/
function searchAndSort($modelInstance, $tableName, $relations, $similarityColumn)
{
  $q = request()->input('q', '');
  $filter = request()->input('filter', '');
  $sorts = explode(',', request()->input('sort', ''));
  $confirmedSort = confirmColumns($sorts, $tableName, $relations);

  $arr = buildSearchSortFilterConditions($q, $filter, $confirmedSort);

  $class = get_class($modelInstance);
  $traits = class_uses($class);

  return $modelInstance
    ->when($arr['qOnly'], function () use ($q, $modelInstance, $similarityColumn) {

      /*
       * --------------------------------------------------------------------------
       * SIMILARITY ONLY : control
       * --------------------------------------------------------------------------
       */

      return $modelInstance->similarity($similarityColumn, $q);
    })
    ->when($arr['qFilter'], function () use ($q, $filter, $modelInstance, $similarityColumn, $traits) {

      /*
       * --------------------------------------------------------------------------
       * SIMILARITY + FILTER : controls
       * --------------------------------------------------------------------------
       */

      [$criteria, $value] = $modelInstance->confirmFilter();
      if ($value == "null") $value = null;

      // timestamp filters
      if (
        $criteria == $modelInstance->getTable() . '.start_at' ||
        $criteria == $modelInstance->getTable() . '.end_at' ||
        $criteria == $modelInstance->getTable() . '.completed_at' ||
        $criteria == $modelInstance->getTable() . '.registered_at' ||
        $criteria == $modelInstance->getTable() . '.expires_at' ||
        $criteria == $modelInstance->getTable() . '.created_at' ||
        $criteria == $modelInstance->getTable() . '.updated_at'
      ) {
        return $modelInstance
          ->similarity($similarityColumn, $q)
          ->where($criteria, 'like', "%{$value}%");
      }

      // TRAITS
      if ($criteria == 'category' && in_array(HasCategories::class, $traits))
        return $modelInstance->filterByCategory(
          $modelInstance->similarity($similarityColumn, $q), $value
        );

      if ($criteria == 'city' && in_array(HasCity::class, $traits))
        return $modelInstance->filterByCity(
          $modelInstance->similarity($similarityColumn, $q), $value
        );

      if ($criteria == 'role' && in_array(HasRoles::class, $traits))
        return $modelInstance
          ->similarity($similarityColumn, $q)
          ->role($value);

      // DEFAULT
      return $modelInstance
        ->similarity($similarityColumn, $q)
        ->where($criteria, $value);
    })
    ->when($arr['sortFilter'], function () use ($sorts, $modelInstance, $traits) {

      /*
       * --------------------------------------------------------------------------
       * SORT + FILTER : controls
       * --------------------------------------------------------------------------
       */

      [$criteria, $value] = $modelInstance->confirmFilter();

      // timestamp filters
      if (
        $criteria == $modelInstance->getTable() . '.start_at' ||
        $criteria == $modelInstance->getTable() . '.end_at' ||
        $criteria == $modelInstance->getTable() . '.completed_at' ||
        $criteria == $modelInstance->getTable() . '.registered_at' ||
        $criteria == $modelInstance->getTable() . '.expires_at' ||
        $criteria == $modelInstance->getTable() . '.created_at' ||
        $criteria == $modelInstance->getTable() . '.updated_at'
      ) {
        return $modelInstance
          ->orderQuery($sorts)
          ->where($criteria, 'like', "%{$value}%");
      }

      // TRAITS
      if (
        $criteria == 'category' && in_array(HasCategories::class, $traits))
        return $modelInstance->filterByCategory(
          $modelInstance->orderQuery($sorts), $value
        );

      if ($criteria == 'city' && in_array(HasCity::class, $traits))
        return $modelInstance->filterByCity(
          $modelInstance->orderQuery($sorts), $value
        );

      if ($criteria == 'role' && in_array(HasRoles::class, $traits))
        return $modelInstance
          ->orderQuery($sorts)
          ->role($value);

      // DEFAULT
      return $modelInstance
        ->orderQuery($sorts)
        ->where($criteria, $value);
    })
    ->when($arr['sortOnly'], function () use ($sorts, $modelInstance) {
      /*
       * --------------------------------------------------------------------------
       * SORT ONLY: control
       * --------------------------------------------------------------------------
       */

      return $modelInstance->orderQuery($sorts);
    })
    ->when($arr['filterOnly'], function () use ($modelInstance, $traits) {

      /*
       * --------------------------------------------------------------------------
       * FILTER ONLY: controls
       * --------------------------------------------------------------------------
       */

      [$criteria, $value] = $modelInstance->confirmFilter();
      if ($value == "null") $value = null;
      // timestamp filters
      if (
        $criteria == $modelInstance->getTable() . '.start_at' ||
        $criteria == $modelInstance->getTable() . '.end_at' ||
        $criteria == $modelInstance->getTable() . '.completed_at' ||
        $criteria == $modelInstance->getTable() . '.registered_at' ||
        $criteria == $modelInstance->getTable() . '.expires_at' ||
        $criteria == $modelInstance->getTable() . '.created_at' ||
        $criteria == $modelInstance->getTable() . '.updated_at'
      ) {
        return $modelInstance->where($criteria, 'like', "%{$value}%");
      }

      // TRAITS
      if (
        $criteria == 'category' && in_array(HasCategories::class, $traits))
        return $modelInstance->filterByCategory($modelInstance::query(), $value);

      if ($criteria == 'city' && in_array(HasCity::class, $traits))
        return $modelInstance->filterByCity($modelInstance::query(), $value);

      if ($criteria == 'role' && in_array(HasRoles::class, $traits))
        return $modelInstance->role($value);

      // DEFAULT
      return $modelInstance->where($criteria, $value);
    })
    ->when($arr['default'], function () use ($modelInstance) {

      /*
       * --------------------------------------------------------------------------
       * DEFAULT: controls
       * --------------------------------------------------------------------------
       */

      return $modelInstance;
    });
}
