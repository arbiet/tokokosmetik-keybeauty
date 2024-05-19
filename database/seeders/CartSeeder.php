<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil 20 pelanggan acak
        $customers = User::where('usertype', 'customer')->inRandomOrder()->limit(20)->get();

        // Ambil produk acak
        $products = Product::inRandomOrder()->limit(4)->get();

        foreach ($customers as $customer) {
            foreach ($products as $product) {
                $quantity = rand(1, 5);

                Cart::create([
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}
