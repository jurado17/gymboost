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
        
        // Obtener todos los ids de usuarios existentes
        $userIds = DB::table('users')->pluck('id');

        // Verifica que haya usuarios en la tabla 'users'
        if ($userIds->isEmpty()) {
            $this->command->info('No users found, skipping user_addresses seeding.');
            return;
        }

        foreach ($userIds as $userId) {
            DB::table('user_addresses')->insert([
                'type' => $faker->word,
                'address1' => $faker->address,
                'city' => $faker->city,
                'province' => $faker->state,
                'zip_code' => $faker->postcode,
                'isMain' => $faker->boolean,
                'country_code' => $faker->countryCode,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
