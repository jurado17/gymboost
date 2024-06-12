<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 10) as $index) {
            DB::table('products')->insert([
                'name' => $faker->word,
                'slug' => $faker->slug,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 1, 100),
                'created_by' => $faker->optional()->numberBetween(1, 10),
                'updated_by' => $faker->optional()->numberBetween(1, 10),
                'brand_id' => $faker->numberBetween(1, 10),
                'category_id' => $faker->numberBetween(1, 10),
                'deleted_by' => $faker->numberBetween(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
