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
            'T-Shirt',
            'Hoodie / Jacket',
            'Kemeja',
            'Celana',
            'Aksesoris Fashion',
        ];

        foreach ($categories as $name) {
            DB::table('product_categories')->insert([
                'name'        => $name,
                'slug'        => Str::slug($name),
            ]);
        }
    }
}
