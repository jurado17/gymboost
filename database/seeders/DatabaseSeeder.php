<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        

        $this->call([
            AdminSeeder::class,
            UsersTableSeeder::class,
            WeightsTableSeeder::class,
            FlavoursTableSeeder::class,
            UserAddressesTableSeeder::class,
            CategoriesTableSeeder::class,
            BrandsTableSeeder::class,
            ProductsTableSeeder::class,
            OrdersTableSeeder::class,
            StockProductsTableSeeder::class,
            OrderItemsTableSeeder::class,
        ]);
    }
}
