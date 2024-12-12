<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Muhamad Adri Muwaffaq Khamid',
            'email' => 'adri@admin.com',
            'password' => Hash::make('12345678'),
            'phone' => '085697752948',
            'unique_number' => "20210140141",
            'role' => 'admin',
        ]);
    }
}
