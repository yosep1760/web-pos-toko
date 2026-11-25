<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DemoProductSeeder extends Seeder
{
    public function run(): void
    {
        $sembako = Category::firstOrCreate(['name' => 'Sembako']);
        $minuman = Category::firstOrCreate(['name' => 'Minuman']);
        $snack   = Category::firstOrCreate(['name' => 'Makanan Ringan']);
        $rt      = Category::firstOrCreate(['name' => 'Kebutuhan Rumah Tangga']);
        Category::firstOrCreate(['name' => 'Alat Tulis'], ['description' => 'Kategori contoh tanpa produk']);

        $data = [
            [$sembako->id, 'MG-001', 'Minyak Goreng 1L',     'liter',  18000, 40],
            [$sembako->id, 'BR-001', 'Beras Premium 5kg',    'karung', 65000, 8],
            [$sembako->id, 'GL-001', 'Gula Pasir 1kg',       'kg',     17000, 0],
            [$minuman->id, 'TB-001', 'Teh Botol 450ml',      'botol',  5000,  15],
            [$minuman->id, 'AQ-001', 'Air Mineral 600ml',    'botol',  3500,  120],
            [$snack->id,   'CP-001', 'Keripik Singkong',     'pcs',    12000, 25],
            [$snack->id,   'WF-001', 'Wafer Cokelat',        'pcs',    2500,  5],
            [$rt->id,      'SB-001', 'Sabun Cuci Piring',    'pcs',    9000,  30],
        ];

        foreach ($data as [$catId, $code, $name, $unit, $price, $stock]) {
            Product::updateOrCreate(
                ['code' => $code],
                ['category_id' => $catId, 'name' => $name, 'unit' => $unit, 'price' => $price, 'stock' => $stock]
            );
        }
    }
}