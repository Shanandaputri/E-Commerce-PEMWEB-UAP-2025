<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            //product 1
            [
                'name'        => 'Gaun Satin Aurora',
                'category_id' => 1,
                'price'       => 650000,
                'stock'       => 8,
                'weight'      => 600,
            ],
            [
                'name'        => 'Dress Pleats Midnight Blue',
                'category_id' => 1,
                'price'       => 720000,
                'stock'       => 6,
                'weight'      => 650,
            ],

            //product 2
            [
                'name'        => 'Blouse Chiffon Ivory',
                'category_id' => 2,
                'price'       => 285000,
                'stock'       => 20,
                'weight'      => 300,
            ],
            [
                'name'        => 'Kemeja Linen Soft',
                'category_id' => 2,
                'price'       => 310000,
                'stock'       => 15,
                'weight'      => 320,
            ],

            //product 3
            [
                'name'        => 'Rok Plisket Mocha',
                'category_id' => 3,
                'price'       => 260000,
                'stock'       => 18,
                'weight'      => 350,
            ],
            [
                'name'        => 'Wide Leg Pants Charcoal',
                'category_id' => 3,
                'price'       => 340000,
                'stock'       => 12,
                'weight'      => 400,
            ],

            //product 4
            [
                'name'        => 'Blazer Classic Almond',
                'category_id' => 4,
                'price'       => 480000,
                'stock'       => 10,
                'weight'      => 700,
            ],
            [
                'name'        => 'Trench Coat',
                'category_id' => 4,
                'price'       => 890000,
                'stock'       => 5,
                'weight'      => 900,
            ],

            //product 5
            [
                'name'        => 'Set Hijab Pashmina Silk Rose',
                'category_id' => 5,
                'price'       => 230000,
                'stock'       => 25,
                'weight'      => 250,
            ],
            [
                'name'        => 'Scarf Hand Painted',
                'category_id' => 5,
                'price'       => 185000,
                'stock'       => 30,
                'weight'      => 200,
            ],
        ];

        foreach ($products as $item) {
            Product::create([
                'store_id'            => 1,
                'product_category_id' => $item['category_id'],
                'name'                => $item['name'],
                'slug'                => Str::slug($item['name']),
                'description'         => $item['name'] . ' dari koleksi butik elegan.',
                'condition'           => 'new',
                'price'               => $item['price'],
                'weight'              => $item['weight'],
                'stock'               => $item['stock'],
            ]);
        }
    }
}
