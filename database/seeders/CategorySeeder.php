<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('categories')->insert([
                'name' => 'Kategori ' . $i,
                'description' => 'Deskripsi Kategori ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

