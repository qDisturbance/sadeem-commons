<?php

/**
 * The global pagination offset
 *
 * @return int
 */
function globalPaginationSize(): int
{
  return (int)env('PAGINATION_SIZE', 12);
}

/**
 * The set of icon sizes for flags
 *
 * @return array
 */
function getFlagIconSizes(): array
{
  return [16,24,32,48,64,128];
}
