<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            ['name' => 'Laptop', 'price' => 2500.00, 'stock' => 10],
            ['name' => 'Phone', 'price' => 1200.00, 'stock' => 20],
            ['name' => 'Headphones', 'price' => 200.00, 'stock' => 15],
        ]);
    }
}
