<?php

namespace Database\Seeders;

use App\Models\UserBalance;
use Illuminate\Database\Seeder;

class UserBalanceSeeder extends Seeder
{
    public function run(): void
    {
        // Admin + 2 Member
        for ($userId = 1; $userId <= 3; $userId++) {
            UserBalance::create([
                'user_id' => $userId,
                'balance' => 0,
            ]);
        }
    }
}