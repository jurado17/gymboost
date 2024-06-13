<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderItemsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 10) as $index) {
            DB::table('order_items')->insert([
                'order_id' => $faker->numberBetween(1, 10),
                'product_id' => $faker->numberBetween(1, 10),
                'quantity' => $faker->numberBetween(1, 10),
                'unit_price' => $faker->randomFloat(2, 1, 100),
                'created_by' => $faker->numberBetween(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
