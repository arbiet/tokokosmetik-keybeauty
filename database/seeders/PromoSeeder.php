<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;
use Carbon\Carbon;

class PromoSeeder extends Seeder
{
    public function run()
    {
        Promo::create([
            'promo_code' => 'PROMO10',
            'discount_percentage' => 10.00,
            'maximum_discount' => 10000.00,
            'minimum_purchase' => 100000.00,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
        ]);

        Promo::create([
            'promo_code' => 'DISCOUNT20',
            'discount_percentage' => 20.00,
            'maximum_discount' => 20000.00,
            'minimum_purchase' => 200000.00,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
        ]);

        Promo::create([
            'promo_code' => 'SALE30',
            'discount_percentage' => 30.00,
            'maximum_discount' => 30000.00,
            'minimum_purchase' => 300000.00,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
        ]);

        Promo::create([
            'promo_code' => 'SAVE40',
            'discount_percentage' => 40.00,
            'maximum_discount' => 40000.00,
            'minimum_purchase' => 400000.00,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
        ]);

        Promo::create([
            'promo_code' => 'BIGSALE50',
            'discount_percentage' => 50.00,
            'maximum_discount' => 50000.00,
            'minimum_purchase' => 500000.00,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
        ]);
    }
}
