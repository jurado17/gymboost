<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserAddressesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 10) as $index) {
            DB::table('user_addresses')->insert([
                'type' => $faker->word,
                'address1' => $faker->address,
                'city' => $faker->city,
                'province' => $faker->state,
                'zip_code' => $faker->postcode,
                'isMain' => $faker->boolean,
                'country_code' => $faker->countryCode,
                'user_id' => $faker->numberBetween(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
