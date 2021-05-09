<?php


namespace Sadeem\Commons\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Sadeem\Commons\Models\Category;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Output\ConsoleOutput;

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

      if ($data[4] == 'NULL') $data[4] = null;

      $filename = 'public/pictures/freelancers/picsum_' . Str::uuid() . '.jpg';
//      $thumb = 'public/pictures/freelancers/thumbs/thumb_' . basename($filename);
      $url = 'https://picsum.photos/480/480';
      Storage::put($filename, file_get_contents($url));

      $msg = "downloaded: {$filename}";
      $output = new ConsoleOutput();
      $output->writeln("<info>$msg</info>");

      Category::create([
        'id' => $data[0],
        'name' => $data[1],
        'img' => $filename,
        'is_disabled' => $data[3],
        'parent_id' => $data[4]
      ]);
    }

    $this->info("Sadeem/Commons/Category samples have been seeded");
  }

}
