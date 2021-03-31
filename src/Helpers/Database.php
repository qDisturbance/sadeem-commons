<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

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
 * Takes a filter and check it over table columns
 * can make a default column if the check fails
 * can add relation columns to pass the check
 *
 * @param $filter
 * @param $tableName
 * @param $default
 * @param $relationColumns
 * @return array
 */
function confirmFilter($filter, $tableName, $default, $relationColumns = []): array
{
  if (!strpos($filter, ':')) return [$default, ''];

  [$criteria, $value] = explode(':', $filter);

  $exists = confirmColumns([$criteria], $tableName);

  if ($exists) return [$tableName.'.'.$criteria, $value];
//  if ($criteria == 'role') return [$criteria, $value];
  if (in_array($criteria, $relationColumns)) return [$criteria, $value];

  return [$tableName.'.'.$default, $value];
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

/*
 * Similarity By Column string search for all models.
 *
 * @param $modelInstance
 * @param $column
 * @param $q
 * @return mixed
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

  $citiesTableName = config('sadeem.table_names.cities');
  $citiesColumnName = config('sadeem.column_names.city_id');

  $modelHasCategoriesTableName = config('sadeem.table_names.model_has_categories');
  $modelMorphKey = config('sadeem.column_names.model_morph_key');
  $categoriesTableName = config('sadeem.table_names.categories');

  $query = $modelInstance::query();

  foreach ($sorts as $sortColumn) {

    // remove empty spaces
    $sortColumn = str_replace(' ', '', $sortColumn);

    // decide the order direction
    $sortDirection = str_starts_with($sortColumn, '-') ? 'desc' : 'asc';

    // trim the order direction
    $sortColumn = str_replace('-', '', $sortColumn);

    if ($sortColumn == 'city_id' || $sortColumn == 'city') {

      // sort by city name instead of city_id

      $query
        ->join($citiesTableName,
          $modelInstance->getTable() . "." . $citiesColumnName,
          "=", "{$citiesTableName}.id")
        ->select("{$modelInstance->getTable()}.*")
        ->orderBy($citiesTableName . ".name", $sortDirection);
    } elseif ($sortColumn == 'category') {

      // sort by category morph relation

      $query
        ->join(
          "{$modelHasCategoriesTableName}",
          "{$modelHasCategoriesTableName}.{$modelMorphKey}",
          '=',
          $modelInstance->getTable() . ".id"
        )
        ->join(
          "{$categoriesTableName}",
          "{$categoriesTableName}.id",
          '=',
          "{$modelHasCategoriesTableName}.category_id"
        )
        ->select(
          $modelInstance->getTable() . ".*",
          "{$categoriesTableName}.name as category_name"
        )
        ->orderBy("category_name", $sortDirection);
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
