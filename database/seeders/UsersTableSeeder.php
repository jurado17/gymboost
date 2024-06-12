<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 10) as $index) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->email,
                'tel' => $faker->phoneNumber,
                'birth_date' => $faker->optional()->date,
                'email_verified_at' => $faker->optional()->dateTime,
                'password' => Hash::make('password'),
                'isAdmin' => $faker->boolean,
                'remember_token' => $faker->optional()->sha1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
