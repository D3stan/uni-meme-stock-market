<?php

namespace App\Services;

use App\Models\Market\Category;
use Illuminate\Database\Eloquent\Collection;

class CreateService
{
    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return Category::all();
    }
}
