<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        Store::create([
            'user_id'     => 2, // Member Satu (id = 2)
            'name'        => 'Toko Member Satu',
            'logo'        => 'default-store-logo.png', // atau '' bebas
            'about'       => 'Toko pertama member satu yang menjual berbagai produk.',
            'phone'       => '081234567890',
            'address_id'  => 1,
            'city'        => 'Bandung',
            'address'     => 'Jl. Contoh No. 1',
            'postal_code' => '40111',
            'is_verified' => true,
        ]);
    }
}
