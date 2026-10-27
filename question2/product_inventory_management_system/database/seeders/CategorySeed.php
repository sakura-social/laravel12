<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // create 10 categories with random names
        for ($i = 0; $i < 10; $i++) {   
            Category::create([
                'name' => fake()->word(),
                'parent_id' => null,
            ]);
        }
    }
}
