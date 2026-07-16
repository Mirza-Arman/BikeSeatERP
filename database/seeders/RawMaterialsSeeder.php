<?php

namespace Database\Seeders;

use App\Models\RawMaterial;
use App\Models\MaterialCategory;
use Illuminate\Database\Seeder;

class RawMaterialsSeeder extends Seeder
{
    public function run()
    {
        $foam = MaterialCategory::firstWhere('name', 'Foam');
        $leather = MaterialCategory::firstWhere('name', 'Leather');

        $items = [
            [
                'category_id' => $foam->id,
                'material_code' => 'RM-FOAM-001',
                'name' => 'Seat Foam Pad',
                'unit' => 'pcs',
                'minimum_stock' => 10,
                'current_stock' => 200,
                'cost_per_unit' => 5.50,
                'description' => 'High-density foam for seats',
                'status' => 'active',
            ],
            [
                'category_id' => $leather->id,
                'material_code' => 'RM-LEATH-001',
                'name' => 'Seat Leather Cover',
                'unit' => 'pcs',
                'minimum_stock' => 20,
                'current_stock' => 150,
                'cost_per_unit' => 12.00,
                'description' => 'Synthetic leather cover',
                'status' => 'active',
            ],
        ];

        foreach ($items as $it) {
            RawMaterial::updateOrCreate(['material_code' => $it['material_code']], $it);
        }
    }
}
