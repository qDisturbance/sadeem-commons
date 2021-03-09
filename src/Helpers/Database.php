<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

/**
 * Match the sortBy string to a column in a table.
 *
 * @param string $tableName
 * @param array $sorts
 * @return bool
 */
function confirmColumns($sorts, $tableName): bool
{
  $columns = Schema::getColumnListing($tableName);
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
 * @return mixed
 */
function similarityByColumn($modelInstance, $column, $q): mixed
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
 * @param $sorts
 * @return Builder
 */
function orderQuery($modelInstance, $sorts): Builder
{
  $query = $modelInstance::query();

  foreach ($sorts as $sortColumn) {

    // remove empty spaces
    $sortColumn = str_replace(' ', '', $sortColumn);

    // decide the order direction
    $sortDirection = str_starts_with($sortColumn, '-') ? 'desc' : 'asc';

    // trim the order direction
    $sortColumn = str_replace('-', '', $sortColumn);

    $query->orderBy($sortColumn, $sortDirection);
  }

  return $query;

}
