<?php

namespace Database\Seeders;

use App\Category; // تأكد من استخدام المسار الصحيح للنموذج
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Laptops', 'slug' => 'laptops'],
            ['name' => 'Desktops', 'slug' => 'desktops'],
            ['name' => 'Mobile Phones', 'slug' => 'mobile-phones'],
            ['name' => 'Tablets', 'slug' => 'tablets'],
            ['name' => 'TVs', 'slug' => 'tvs'],
            ['name' => 'Digital Cameras', 'slug' => 'digital-cameras'],
            ['name' => 'Appliances', 'slug' => 'appliances'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']], // شرط البحث
                [
                    'slug' => $category['slug'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );
        }
    }
}
