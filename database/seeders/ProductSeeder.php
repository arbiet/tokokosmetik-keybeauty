<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil semua kategori dari tabel categories
        $categories = DB::table('categories')->pluck('id')->toArray();

        // Loop untuk membuat produk
        for ($i = 0; $i < count($categories); $i++) {
            // Ambil jumlah produk untuk kategori ini, antara 10 hingga 15
            $productCount = rand(10, 15);

            // Ambil kategori secara acak
            $categoryId = $categories[$i];

            // Buat produk untuk kategori ini
            for ($j = 1; $j <= $productCount; $j++) {
                DB::table('products')->insert([
                    'name' => 'Produk ' . $j . ' Kategori ' . $categoryId,
                    'slug' => 'produk-' . $j . '-kategori-' . $categoryId,
                    'description' => 'Deskripsi Produk ' . $j . ' Kategori ' . $categoryId,
                    'category_id' => $categoryId,
                    'price' => rand(10000, 200000), // Harga produk random antara 50 dan 200
                    'stock' => rand(10, 100), // Stok produk random antara 10 dan 100
                    'image' => 'default.png',
                    'weight' => rand(300, 1000), // Berat produk random antara 1 dan 10
                    'length' => rand(10, 100), // Panjang produk random antara 10 dan 100
                    'width' => rand(10, 100), // Lebar produk random antara 10 dan 100
                    'height' => rand(10, 100), // Tinggi produk random antara 10 dan 100
                    'status' => 'available', // Status produk default 'available'
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
