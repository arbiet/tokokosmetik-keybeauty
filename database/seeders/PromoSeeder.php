<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;

class PromoSeeder extends Seeder
{
    public function run()
    {
        Promo::create([
            'promo_code' => 'PROMO10',
            'discount_amount' => 10.00,
            'minimum_purchase' => 100.00,
        ]);

        Promo::create([
            'promo_code' => 'DISCOUNT20',
            'discount_amount' => 20.00,
            'minimum_purchase' => 150.00,
        ]);

        Promo::create([
            'promo_code' => 'SALE30',
            'discount_amount' => 30.00,
            'minimum_purchase' => 200.00,
        ]);

        Promo::create([
            'promo_code' => 'SAVE40',
            'discount_amount' => 40.00,
            'minimum_purchase' => 250.00,
        ]);

        Promo::create([
            'promo_code' => 'BIGSALE50',
            'discount_amount' => 50.00,
            'minimum_purchase' => 300.00,
        ]);
    }
}
