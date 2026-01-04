<?php

namespace Database\Factories\Market;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = \App\Models\Market\Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'slug' => fn (array $attributes) => \Illuminate\Support\Str::slug($attributes['name']),
        ];
    }
}
