<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Member 1
        User::firstOrCreate(
            ['email' => 'member1@example.com'],
            [
                'name' => 'Member Satu',
                'password' => Hash::make('password'),
                'role' => 'member',
            ]
        );

        // Member 2
        User::firstOrCreate(
            ['email' => 'member2@example.com'],
            [
                'name' => 'Member Dua',
                'password' => Hash::make('password'),
                'role' => 'member',
            ]
        );
    }
}
