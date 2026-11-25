<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::forceCreate([
            'name' => 'Admin Toko',
            'email' => 'admin@barokahmart.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::forceCreate([
            'name' => 'Kasir Rina',
            'email' => 'kasir@barokahmart.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $this->call(CategorySeeder::class);
    }
}
