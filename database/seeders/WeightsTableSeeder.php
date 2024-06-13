<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WeightsTableSeeder extends Seeder
{
    public function run()
    {
        $specificWeights = [
            ['name' => 'Monodosis(50g)', 'weight_g' => 50, 'price' => 0.05],
            ['name' => '200g', 'weight_g' => 200, 'price' => 0.20],
            ['name' => '500g', 'weight_g' => 500, 'price' => 0.50],
            ['name' => '1Kg', 'weight_g' => 1000, 'price' => 1.00],
            ['name' => '2Kg', 'weight_g' => 2000, 'price' => 2.00],
            ['name' => '4Kg', 'weight_g' => 4000, 'price' => 4.00],
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
