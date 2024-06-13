<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
class WeightsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $specificWeights = [
            ['name' => 'Light', 'weight_g' => 1000, 'price' => 10.00],
            ['name' => 'Medium', 'weight_g' => 2000, 'price' => 18.00],
            ['name' => 'Heavy', 'weight_g' => 3000, 'price' => 25.00],
            ['name' => 'Super Heavy', 'weight_g' => 4000, 'price' => 32.00],
            ['name' => 'Ultra Heavy', 'weight_g' => 5000, 'price' => 40.00],
        ];

        foreach ($specificWeights as $weight) {
            DB::table('weights')->insert([
                'name' => $weight['name'],
                'weight_g' => $weight['weight_g'],
                'price' => $weight['price'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
