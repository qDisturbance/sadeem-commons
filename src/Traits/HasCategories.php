<?php


namespace Sadeem\Commons\Traits;


use Sadeem\Commons\Models\Category;

trait HasCategories
{
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
