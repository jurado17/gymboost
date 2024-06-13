<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 10) as $index) {
            DB::table('orders')->insert([
                'total_price' => $faker->randomFloat(2, 20, 500),
                'status' => $faker->randomElement(['pending', 'completed', 'cancelled']),
                'sessison_id' => $faker->uuid,
                'user_address_id' => $faker->numberBetween(1, 10),
                'created_by' => $faker->optional()->numberBetween(1, 10),
                'updated_by' => $faker->optional()->numberBetween(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
