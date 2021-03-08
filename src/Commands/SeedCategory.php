<?php


namespace Sadeem\Commons\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Sadeem\Commons\Models\Category;

class SeedCategory extends Command
{
  protected $signature = 'sadeem:seed-categories';

  protected $description = 'Seed the sample categories ';

  public function handle()
  {

    $csvFile = File::exists(public_path('sadeem_tech/categories.csv'));

    // if no csv sample data published use the one in the package
    if(!$csvFile)
    {
      $csvFile = file(__DIR__.'/../resources/assets/categories.csv') ;
    } else {
      $csvFile = file(public_path('sadeem_tech/categories.csv'));
    }

    for ($i = 1; $i < count($csvFile); $i++) {
      $data = str_getcsv($csvFile[$i]);

      if ($data[3] == "null") $data[3] = null;
      if ($data[4] == "null") $data[4] = null;

      $category = Category::make([
          'id' => $data[0],
          'name' => $data[1],
          'is_disabled' => $data[2],
          'parent_id' => $data[3],
          'model_name' => $data[4],
        ]);
      $category->timestamps = config('category_table_timestamps', false);
      $category->save();
    }

    $this->info("Sadeem/Commons/Category samples have been seeded");
  }

}
