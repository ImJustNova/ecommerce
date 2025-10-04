<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->delete();
        DB::statement("ALTER TABLE products AUTO_INCREMENT = 1");

        DB::table('products')->insert([
            //sample products
            ['name' => 'Laptop', 'price' => 1200.00, 'stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Phone', 'price' => 800.00, 'stock' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Headphones', 'price' => 150.00, 'stock' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Smartwatch', 'price' => 200.00, 'stock' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tablet', 'price' => 400.00, 'stock' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keyboard', 'price' => 80.00, 'stock' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mouse', 'price' => 45.00, 'stock' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Camera', 'price' => 950.00, 'stock' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Speaker', 'price' => 120.00, 'stock' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Monitor', 'price' => 350.00, 'stock' => 18, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}