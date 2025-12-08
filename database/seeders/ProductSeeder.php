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
            // ===== KATEGORI 1 =====
            [
                'name'        => 'POLO RAJUTAN KOMBINASI',
                'category_id' => 1,
                'price'       => 899900,
                'stock'       => 8,
                'weight'      => 600,
                'description' => 'Polo rajutan kombinasi dengan tekstur lembut dan detail warna kontras yang cocok untuk tampilan kasual maupun semi-formal.',
            ],
            [
                'name'        => 'JERSI RAJUTAN KERAH LIPAT',
                'category_id' => 1,
                'price'       => 899900,
                'stock'       => 6,
                'weight'      => 650,
                'description' => 'Jersi rajutan dengan kerah lipat yang memberikan kesan rapi namun tetap nyaman untuk dipakai sehari-hari.',
            ],
            [
                'name'        => 'POLO 95% WOL RAJUTAN',
                'category_id' => 1,
                'price'       => 2299000,
                'stock'       => 6,
                'weight'      => 650,
                'description' => 'Polo rajutan dengan komposisi 95% wol yang hangat dan premium, ideal untuk cuaca dingin dan tampilan elegan.',
            ],

            // ===== KATEGORI 2 =====
            [
                'name'        => 'JAKET RAJUTAN BOUCLÉ',
                'category_id' => 2,
                'price'       => 1399000,
                'stock'       => 8,
                'weight'      => 600,
                'description' => 'Jaket rajutan bouclé dengan tekstur unik yang memberikan kesan mewah dan stylish di setiap kesempatan.',
            ],
            [
                'name'        => 'BLAZER BAHAN WOL ZW',
                'category_id' => 2,
                'price'       => 2599000,
                'stock'       => 6,
                'weight'      => 650,
                'description' => 'Blazer berbahan wol ZW dengan potongan tailored yang cocok untuk tampilan formal maupun smart casual.',
            ],
            [
                'name'        => 'JAKET BOMBER WOL ZW',
                'category_id' => 2,
                'price'       => 2299000,
                'stock'       => 6,
                'weight'      => 650,
                'description' => 'Jaket bomber berbahan wol ZW dengan desain modern yang hangat dan nyaman dipakai sepanjang hari.',
            ],

            // ===== KATEGORI 3 =====
            [
                'name'        => 'KEMEJA SATIN ZW',
                'category_id' => 3,
                'price'       => 899900,
                'stock'       => 8,
                'weight'      => 600,
                'description' => 'Kemeja satin ZW dengan permukaan berkilau halus yang memberikan sentuhan elegan pada setiap gaya.',
            ],
            [
                'name'        => 'KEMEJA RENDA ZW',
                'category_id' => 3,
                'price'       => 1399000,
                'stock'       => 6,
                'weight'      => 650,
                'description' => 'Kemeja renda ZW dengan detail renda feminin yang cocok untuk acara spesial maupun gaya semi-formal.',
            ],
            [
                'name'        => 'ATASAN JATUH ASIMETRIS',
                'category_id' => 3,
                'price'       => 1099000,
                'stock'       => 6,
                'weight'      => 650,
                'description' => 'Atasan dengan potongan jatuh asimetris yang unik, memberikan siluet modern dan stylish.',
            ],

            // ===== KATEGORI 4 =====
            [
                'name'        => 'JEANS ZW WIDE LEG TIRO MD',
                'category_id' => 4,
                'price'       => 1699000,
                'stock'       => 8,
                'weight'      => 600,
                'description' => 'Jeans ZW model wide leg dengan potongan mid-rise yang nyaman dan trendi untuk sehari-hari.',
            ],
            [
                'name'        => 'JEANS ZW WIDE LEG TIRO',
                'category_id' => 4,
                'price'       => 1699000,
                'stock'       => 6,
                'weight'      => 650,
                'description' => 'Jeans ZW wide leg dengan siluet lebar yang memberikan kesan kaki jenjang dan modern.',
            ],
            [
                'name'        => 'JEANS TRF SKINNY TIRO ALTO',
                'category_id' => 4,
                'price'       => 759900,
                'stock'       => 6,
                'weight'      => 650,
                'description' => 'Jeans TRF skinny dengan potongan pinggang tinggi (tiro alto) yang membentuk siluet tubuh dengan maksimal.',
            ],

            // ===== KATEGORI 5 (AKSESORIS) =====
            [
                'name'        => 'ANTING BUNDAR BERKILAU',
                'category_id' => 5,
                'price'       => 459900,
                'stock'       => 8,
                'weight'      => 600,
                'description' => 'Anting bundar dengan detail berkilau yang menjadi statement elegan untuk setiap tampilan.',
            ],
            [
                'name'        => 'KALUNG BOLA MUTIARA',
                'category_id' => 5,
                'price'       => 659900,
                'stock'       => 6,
                'weight'      => 650,
                'description' => 'Kalung dengan gantungan bola mutiara yang manis dan feminin, cocok untuk melengkapi outfit formal maupun kasual.',
            ],
            [
                'name'        => 'ANTING BUNGA TENGKORAK',
                'category_id' => 5,
                'price'       => 459900,
                'stock'       => 6,
                'weight'      => 650,
                'description' => 'Anting dengan desain bunga tengkorak yang edgy dan unik, pas untuk kamu yang suka gaya bold dan berbeda.',
            ],
        ];

        foreach ($products as $item) {
            Product::create([
                'store_id'            => 1,
                'product_category_id' => $item['category_id'],
                'name'                => $item['name'],
                'slug'                => Str::slug($item['name']),
                'description'         => $item['description'],
                'condition'           => 'new',
                'price'               => $item['price'],
                'weight'              => $item['weight'],
                'stock'               => $item['stock'],
            ]);
        }
    }
}
