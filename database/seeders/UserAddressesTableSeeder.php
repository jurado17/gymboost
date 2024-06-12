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
        $userIds = DB::table('users')->pluck('id')->all();

        // Verifica que haya usuarios en la tabla 'users'
        if (empty($userIds)) {
            $this->command->info('No users found, skipping user_addresses seeding.');
            return;
        }

        foreach (range(1, 10) as $index) {
            // Obtener un user_id aleatorio válido
            $userId = $faker->randomElement($userIds);

            try {
                DB::table('user_addresses')->insert([
                    'type' => 'Domicilio',
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
            } catch (\Exception $e) {
                $this->command->error("Error inserting user address for user_id $userId: " . $e->getMessage());
            }
        }
    }
}
