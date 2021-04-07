<?php

namespace Sadeem\Commons\Models;

use Sadeem\Commons\Traits\HasCity;
use Illuminate\Database\Eloquent\Model;

class PrayerTime extends Model
{
  use HasCity;

  protected $with = ['city'];
  protected $primaryKey = null;
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    "day",
    "emsak",
    "fajer",
    "sherook",
    "dahor",
    "aser",
    "keroob",
    "makrib",
    "esha",
    "city_id"
  ];
}
