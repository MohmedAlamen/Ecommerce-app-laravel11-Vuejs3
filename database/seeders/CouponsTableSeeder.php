<?php

namespace Database\Seeders;

use App\Coupon;
use Illuminate\Database\Seeder;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coupon::updateOrInsert(
            ['code' => 'ABC123'], // البحث عن الكود
            [
                'type' => 'fixed',
                'value' => 3000,
            ]
        );

        Coupon::updateOrInsert(
            ['code' => 'DEF456'], // البحث عن الكود
            [
                'type' => 'percent',
                'percent_off' => 50,
            ]
        );
    }
}
