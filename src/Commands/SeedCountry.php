<?php


namespace Sadeem\Commons\Commands;

use Illuminate\Console\Command;
use Sadeem\Commons\Models\Country;
use Illuminate\Support\Facades\File;

class SeedCountry extends Command
{
  protected $signature = 'sadeem:seed-countries';

  protected $description = 'Seed the standard countries ';

  public function handle()
  {
    $csvFile = File::exists(storage_path("app/public/sadeem/countries.csv"));

    if (!$csvFile) {
      $csvFile = file(__DIR__ . '/../resources/assets/countries.csv');
    } else {
      $csvFile = file(storage_path("app/public/sadeem/countries.csv"));
    }

    for ($i = 1; $i < count($csvFile); $i++) {
      $data = str_getcsv($csvFile[$i]);

      if (strlen($data[2]) > 3) $data[2] = null;
      if ($data[3] == "NULL") $data[3] = null;

      Country::create([
        'id' => $i,
        'iso' => $data[1],
        'iso3' => $data[2],
        'num_code' => $data[3],
        'phone_code' => $data[4],
        'name' => $data[5],
        'en_name' => $data[6],
        'ar_name' => $data[7],
      ]);
    }

    $this->info("Sadeem/Commons/Country standard data have been seeded");
  }

}
