<?php

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
function similarityByColumn($modelInstance, $column, $q)
{
  return $modelInstance->selectRaw("*, similarity({$column}, ?) as difference", ["{$q}"])
    ->whereRaw("similarity({$column}, ?) > ?", ["{$q}", 0.1])
    ->orderBy('difference', 'desc');
}
