<?php

namespace Database\Seeders;

use App\Models\Market\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Animali', 'slug' => 'animali'],
            ['name' => 'Reaction', 'slug' => 'reaction'],
            ['name' => 'Politica', 'slug' => 'politica'],
            ['name' => 'Sport', 'slug' => 'sport'],
            ['name' => 'Tech', 'slug' => 'tech'],
            ['name' => 'Università', 'slug' => 'universita'],
            ['name' => 'Gaming', 'slug' => 'gaming'],
            ['name' => 'Crypto', 'slug' => 'crypto'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
