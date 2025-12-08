<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $jumlahKategori = 5;
        $produkPerKategori = 3;
        $fotoPerProduk = 3;

        $totalProduk = $jumlahKategori * $produkPerKategori;

        for ($product = 1; $product <= $totalProduk; $product++) {

            // Hitung kategori saat ini
            $kategori = ceil($product / $produkPerKategori);

            // Produk ke berapa dalam kategori
            $produkKe = $product % $produkPerKategori;
            if ($produkKe == 0) $produkKe = $produkPerKategori;

            for ($foto = 1; $foto <= $fotoPerProduk; $foto++) {

                ProductImage::create([
                    'product_id'   => $product,
                    'image'        => "images/products/product-{$kategori}-{$produkKe}-{$foto}.jpg",
                    'is_thumbnail' => $foto === 1,  // Foto 1 jadi thumbnail
                ]);
            }
        }
    }
}
