<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

/**
 * Match the sortBy string to a column in a table.
 *
 * @param $tableName
 * @param $sortBy
 * @return bool
 */
function confirmColumn($sortBy, $tableName): bool
{
  $columns = Schema::getColumnListing($tableName);
  return in_array($sortBy, $columns);
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
    $sortColumn = trim($sortColumn);

    // decide the order direction
    $sortDirection = str_starts_with($sortColumn, '-') ? 'desc' : 'asc';

    // trim the order direction
    $sortColumn = ltrim($sortColumn, '-');

    $query->orderBy($sortColumn, $sortDirection);
  }

  return $query;

}
