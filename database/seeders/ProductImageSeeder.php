<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $jumlahKategori   = 5;
        $produkPerKategori = 3;
        $fotoPerProduk     = 3;

        $totalProduk = $jumlahKategori * $produkPerKategori; // 15

        for ($product = 1; $product <= $totalProduk; $product++) {

            // kategori sekarang (1..5)
            $kategori = ceil($product / $produkPerKategori);

            // urutan produk dalam kategori (1..3)
            $produkKe = $product % $produkPerKategori;
            if ($produkKe == 0) $produkKe = $produkPerKategori;

            for ($foto = 1; $foto <= $fotoPerProduk; $foto++) {

                ProductImage::create([
                    'product_id'   => $product,
                    'image'        => "images/products/product-{$kategori}-{$produkKe}-{$foto}.png", 
                    'is_thumbnail' => $foto === 1,  // Foto 1 jadi thumbnail
                ]);
            }
        }
    }
}
