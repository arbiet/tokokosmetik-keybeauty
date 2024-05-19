<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $categories = \App\Models\Category::all();

        foreach ($categories as $category) {
            $count = rand(10, 15); // Random number of products per category

            for ($i = 0; $i < $count; $i++) {
                Product::create([
                    'name' => $faker->word,
                    'description' => $faker->sentence,
                    'category_id' => $category->id,
                    'price' => $faker->randomFloat(2, 10, 100),
                    'stock' => $faker->numberBetween(0, 100),
                    // Add other columns as needed
                ]);
            }
        }
    }
}
