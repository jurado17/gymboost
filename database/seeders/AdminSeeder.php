<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Admin',
            'last_name'=>'Admin',
            'email'=>'luisjurador9@gmail.com',
            'tel'=>123456789,
            'password'=>Hash::make('password'),
            'isAdmin'=>1
        ]);
    }
}
