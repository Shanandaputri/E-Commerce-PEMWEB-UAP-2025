<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // 1 gambar per produk, semua dijadikan thumbnail
        for ($i = 1; $i <= 10; $i++) {
            ProductImage::create([
                'product_id'  => $i,
                'image'       => "images/products/product-{$i}.jpg",
                'is_thumbnail'=> true,
            ]);
        }
    }
}
