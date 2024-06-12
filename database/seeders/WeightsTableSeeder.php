<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class WeightsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 10) as $index) {
            DB::table('weights')->insert([
                'name' => $faker->word,
                'weight_g' => $faker->numberBetween(500, 5000),
                'price' => $faker->randomFloat(2, 0.5, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
