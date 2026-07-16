<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'product_code' => 'PRD-001',
                'product_name' => 'Classic Seat Model A',
                'model' => 'A-100',
                'selling_price' => 49.99,
                'minimum_stock' => 10,
                'current_stock' => 50,
                'status' => 'active',
            ],
            [
                'product_code' => 'PRD-002',
                'product_name' => 'Deluxe Seat Model B',
                'model' => 'B-200',
                'selling_price' => 79.99,
                'minimum_stock' => 5,
                'current_stock' => 25,
                'status' => 'active',
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['product_code' => $p['product_code']], $p);
        }
    }
}
