<?php

namespace Database\Seeders;

use SadeemTech\Commons\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $csvFile = file(public_path('sadeem_tech/categories.csv'));

      for ($i = 1; $i < count($csvFile); $i++) {
        $data = str_getcsv($csvFile[$i]);

        if ($data[3] == "null") $data[3] = null;
        if ($data[4] == "null") $data[4] = null;

        Category::factory()
          ->state([
            'id' => $data[0],
            'name' => $data[1],
            'is_disabled' => $data[2],
            'parent_id' => $data[3],
            'model_name' => $data[4],
          ])
          ->create();
      }
    }
}
