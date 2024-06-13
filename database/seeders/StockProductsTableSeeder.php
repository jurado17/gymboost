<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StockProductsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 10) as $index) {
            DB::table('stock_products')->insert([
                'product_id' => $faker->numberBetween(1, 10),
                'weight_id' => $faker->numberBetween(1, 5),
                'flavour_id' => $faker->numberBetween(1, 10),
                'quantity' => $faker->numberBetween(1, 100),
                'isSotcked' => $faker->boolean,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
