<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PromotionTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 10) as $index) {
            DB::table('promotions')->insert([
                'type' => $faker->randomElement(['producto', 'carrito']), // Tipo: 'producto' o 'carrito'
                'name' => $faker->word, // Nombre del cupón
                'description' => $faker->sentence, // Descripción del cupón
                'discount_code' => strtoupper($faker->bothify('##??##')), // Código de descuento de 5 o 6 caracteres
                'porcentual_discount' => $faker->randomFloat(2, 0, 1), // Descuento porcentual
                'active_until' => $faker->dateTimeBetween('now', '+1 year'), // Fecha hasta la que estará disponible el cupón
                'isActive' => $faker->boolean, // Si el cupón está activo
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
