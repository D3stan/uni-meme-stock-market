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
            ['name' => 'Vita Universitaria', 'slug' => 'vita-universitaria'],
            ['name' => 'Ingegneria', 'slug' => 'ingegneria'],
            ['name' => 'Informatica', 'slug' => 'informatica'],
            ['name' => 'Matematica', 'slug' => 'matematica'],
            ['name' => 'Fisica', 'slug' => 'fisica'],
            ['name' => 'Architettura', 'slug' => 'architettura'],
            ['name' => 'Chimica', 'slug' => 'chimica'],
            ['name' => 'Medicina', 'slug' => 'medicina'],
            ['name' => 'Veterinaria', 'slug' => 'veterinaria'],
            ['name' => 'Giurisprudenza', 'slug' => 'giurisprudenza'],
            ['name' => 'Economia', 'slug' => 'economia'],
            ['name' => 'Scienze Politiche', 'slug' => 'scienze-politiche'],
            ['name' => 'Lettere', 'slug' => 'lettere'],
            ['name' => 'Filosofia', 'slug' => 'filosofia'],
            ['name' => 'Storia', 'slug' => 'storia'],
            ['name' => 'Lingue', 'slug' => 'lingue'],
            ['name' => 'Psicologia', 'slug' => 'psicologia'],
            ['name' => 'Sociologia', 'slug' => 'sociologia'],
            ['name' => 'Scienze della Comunicazione', 'slug' => 'scienze-della-comunicazione'],
            ['name' => 'Scienze della Formazione', 'slug' => 'scienze-della-formazione'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
