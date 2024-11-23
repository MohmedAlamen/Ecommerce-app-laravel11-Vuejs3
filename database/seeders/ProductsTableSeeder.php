<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إدخال أو تحديث المنتجات إذا كانت موجودة بالفعل
        DB::table('products')->updateOrInsert(
            ['name' => 'Product 1'], // شرط البحث (اسم المنتج هنا)
            ['slug' => 'product-1', 'price' => 100, 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('products')->updateOrInsert(
            ['name' => 'Product 2'],
            ['slug' => 'product-2', 'price' => 200, 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('products')->updateOrInsert(
            ['name' => 'Product 3'],
            ['slug' => 'product-3', 'price' => 300, 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('products')->updateOrInsert(
            ['name' => 'Product 4'],
            ['slug' => 'product-4', 'price' => 400, 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('products')->updateOrInsert(
            ['name' => 'Product 5'],
            ['slug' => 'product-5', 'price' => 500, 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('products')->updateOrInsert(
            ['name' => 'Product 6'],
            ['slug' => 'product-6', 'price' => 600, 'created_at' => now(), 'updated_at' => now()]
        );
    }
}
