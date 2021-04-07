<?php


namespace Sadeem\Commons\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Sadeem\Commons\Models\PrayerTime;

class SeedPrayerTimes extends Command
{
  protected $signature = 'sadeem:seed-prayer-times';

  protected $description = 'Seed the standard prayer times ';

  public function handle()
  {
    $csvFile = File::exists(storage_path("app/public/sadeem/prayer_times.csv"));

    // if no csv sample data published use the one in the package
    if (!$csvFile) {
      $csvFile = file(__DIR__ . '/../resources/assets/prayer_times.csv');
    } else {
      $csvFile = file(storage_path("app/public/sadeem/prayer_times.csv"));
    }

    for ($i = 1; $i < count($csvFile); $i++) {

      $data = str_getcsv($csvFile[$i]);

      PrayerTime::create([
        "day" => $data[0],
        "emsak" => $data[1],
        "fajer" => $data[2],
        "sherook" => $data[3],
        "dahor" => $data[4],
        "aser" => $data[5],
        "keroob" => $data[6],
        "makrib" => $data[7],
        "esha" => $data[8],
        "city_id" => $data[9]
      ]);
    }

    $this->info("Sadeem/Commons/PrayerTime standard data have been seeded");
  }

}
