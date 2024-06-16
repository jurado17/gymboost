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
        $promotionIds = DB::table('promotions')->pluck('id')->toArray();

        foreach(range(1, 10) as $index) {
            DB::table('products')->insert([
                'name' => $faker->word,
                'slug' => $faker->slug,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 1, 100),
                'published' => $faker->randomFloat(0,1),
                'promotion_id' => $faker->optional()->randomElement($promotionIds), // ID de una promoción existente o null
                'created_by' => $faker->optional()->numberBetween(1, 10),
                'updated_by' => $faker->optional()->numberBetween(1, 10),
                'brand_id' => $faker->numberBetween(1, 10),
                'category_id' => $faker->numberBetween(1, 5),
                'deleted_by' => $faker->optional()->numberBetween(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

