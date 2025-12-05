<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Gaun & Dress',
            'Blouse & Kemeja',
            'Rok & Celana',
            'Outerwear (Blazer & Coat)',
            'Set Hijab & Aksesoris',
        ];

        foreach ($categories as $name) {
            DB::table('product_categories')->insert([
                'name'        => $name,
                'slug'        => Str::slug($name),
                'description' => 'Kategori ' . $name . ' untuk koleksi pakaian elegan.',
            ]);
        }
    }
}
