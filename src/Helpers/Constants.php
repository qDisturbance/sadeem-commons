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

function getDomain(): string
{
  return (string)config('roya.domain', '127.0.0.1:8000');
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

//function getProjectThumbnailWidth(): int
//{
//  return (int)config('project.thumbnail_size', 100);
//}
