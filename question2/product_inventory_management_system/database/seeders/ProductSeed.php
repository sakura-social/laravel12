<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Product::create([
            'name' => 'iPhone 13',
            'description' => 'The latest iPhone model with advanced features.',
            'price' => 999.99,
            'category_id' => 1, // Assuming category_id 1 exists
        ]);   
    }
}
