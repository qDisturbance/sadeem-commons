<?php

namespace Database\Factories;

use SadeemTech\Commons\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'id' => false,
            'name' => false,
            'is_disabled' => false,
            'parent_id' => false,
            'model_name' => false,
        ];
    }
}
