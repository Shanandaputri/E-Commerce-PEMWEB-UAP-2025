<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        // Address untuk Member Satu
        DB::table('addresses')->insert([
            'user_id'     => 2,
            'label'       => 'Alamat Toko',
            'recipient'   => 'Member Satu',
            'phone'       => '081234567890',
            'address'     => 'Jl. Contoh No. 1',
            
            'city'        => 'Bandung',
            'province'    => 'Jawa Barat',
            'postal_code' => '40111',
            'is_primary'  => true,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Address untuk Member Dua
        DB::table('addresses')->insert([
            'user_id'     => 3,
            'label'       => 'Rumah',
            'recipient'   => 'Member Dua',
            'phone'       => '081234567891',
            'address'     => 'Jl. Contoh No. 2',
            'city'        => 'Jakarta',
            'province'    => 'DKI Jakarta',
            'postal_code' => '12345',
            'is_primary'  => true,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        DB::table('addresses')->insert([
            'user_id'     => 1,
            'label'       => 'Kantor',
            'recipient'   => 'Admin',
            'phone'       => '081234567892',
            'address'     => 'Jl. Admin No. 1',
            'city'        => 'Jakarta',
            'province'    => 'DKI Jakarta',
            'postal_code' => '11111',
            'is_primary'  => true,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}