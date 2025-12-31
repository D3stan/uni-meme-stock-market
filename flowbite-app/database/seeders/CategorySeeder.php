<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Classici', 'slug' => 'classici'],
            ['name' => 'Università', 'slug' => 'universita'],
            ['name' => 'Attualità', 'slug' => 'attualita'],
            ['name' => 'Gaming', 'slug' => 'gaming'],
            ['name' => 'Crypto', 'slug' => 'crypto'],
            ['name' => 'Altro', 'slug' => 'altro'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
