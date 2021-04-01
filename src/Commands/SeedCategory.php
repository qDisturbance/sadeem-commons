<?php


namespace Sadeem\Commons\Commands;

use Illuminate\Console\Command;
use Sadeem\Commons\Models\Category;
use Illuminate\Support\Facades\File;

class SeedCategory extends Command
{
  protected $signature = 'sadeem:seed-categories';

  protected $description = 'Seed the sample categories ';

  public function handle()
  {
    $csvFile = File::exists(storage_path("app/public/sadeem/categories.csv"));

    // if no csv sample data published use the one in the package
    if (!$csvFile) {
      $csvFile = file(__DIR__ . '/../resources/assets/categories.csv');
    } else {
      $csvFile = file(storage_path("app/public/sadeem/categories.csv"));
    }

    for ($i = 1; $i < count($csvFile); $i++) {
      $data = str_getcsv($csvFile[$i]);

      if ($data[3] == 'NULL') $data[3] = NULL;

      Category::create([
        'id' => $data[0],
        'name' => $data[1],
        'img' => $data[2],
        'is_disabled' => $data[3],
        'parent_id' => $data[4]
      ]);
    }

    $this->info("Sadeem/Commons/Category samples have been seeded");
  }

}
