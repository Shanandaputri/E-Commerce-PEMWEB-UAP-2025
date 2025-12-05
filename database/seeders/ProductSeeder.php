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
            [
                'name' => 'Laptop Gaming RX',
                'category_id' => 1, // Elektronik
                'price' => 12000000,
                'stock' => 5,
            ],
            [
                'name' => 'Smartphone Z Pro',
                'category_id' => 1,
                'price' => 4500000,
                'stock' => 10,
            ],
            [
                'name' => 'Kaos Oversize Unisex',
                'category_id' => 2, // Fashion
                'price' => 120000,
                'stock' => 30,
            ],
            [
                'name' => 'Jaket Hoodie Tebal',
                'category_id' => 2,
                'price' => 250000,
                'stock' => 20,
            ],
            [
                'name' => 'Lipstick Matte',
                'category_id' => 3, // Kecantikan
                'price' => 75000,
                'stock' => 50,
            ],
            [
                'name' => 'Facial Wash Bright',
                'category_id' => 3,
                'price' => 55000,
                'stock' => 40,
            ],
            [
                'name' => 'Keripik Pedas Level 5',
                'category_id' => 4, // Makanan & Minuman
                'price' => 20000,
                'stock' => 100,
            ],
            [
                'name' => 'Kopi Susu Literan',
                'category_id' => 4,
                'price' => 38000,
                'stock' => 25,
            ],
            [
                'name' => 'Set Piring Keramik',
                'category_id' => 5, // Perlengkapan Rumah
                'price' => 150000,
                'stock' => 15,
            ],
            [
                'name' => 'Sapu Microfiber',
                'category_id' => 5,
                'price' => 45000,
                'stock' => 40,
            ],
        ];

        foreach ($products as $item) {
            Product::create([
                'store_id'            => 1, // toko pertama
                'product_category_id' => $item['category_id'],
                'name'                => $item['name'],
                'slug'                => Str::slug($item['name']),
                'description'         => $item['name'] . ' dari toko Member Satu.',
                'price'               => $item['price'],
                'stock'               => $item['stock'],
                'is_active'           => true,
            ]);
        }
    }
}
