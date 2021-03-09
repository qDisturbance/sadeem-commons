<?php


namespace Sadeem\Commons\Commands;

use Illuminate\Console\Command;
use Sadeem\Commons\Models\City;
use Illuminate\Support\Facades\File;

class SeedCity extends Command
{
  protected $signature = 'sadeem:seed-cities';

  protected $description = 'Seed sample cities data';

  public function handle()
  {
    $csvFile = File::exists(storage_path("app/public/sadeem/cities.csv"));

    if (!$csvFile) {
      $csvFile = file(__DIR__ . '/../resources/assets/cities.csv');
    } else {
      $csvFile = file(storage_path("app/public/sadeem/cities.csv"));
    }

    for ($i = 1; $i < count($csvFile); $i++) {

      $data = str_getcsv($csvFile[$i]);

      City::create([
        'id' => $data[0],
        'name' => $data[1],
        'is_disabled' => $data[2],
        'location' => $data[3]
        ]);
    }

    $this->info("Sadeem/Commons/City standard data have been seeded");
  }

}
