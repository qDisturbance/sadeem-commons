<?php

namespace Sadeem\Commons\Traits;

use DateTime;
use Carbon\Carbon;

trait Iso8601Serialization
{
  /*
   * Prepare a date for array / JSON serialization.
   *
   * @param DateTime $date
   * @return string
   */
  protected function serializeDate($date)
  {
    if (!empty($date)) {
      $formattedDate = new DateTime($date);
      return Carbon::instance($formattedDate)->toIso8601String();
    } else {
      return null;
    }
  }
}
