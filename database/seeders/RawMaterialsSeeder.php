<?php

namespace Database\Seeders;

use App\Models\RawMaterial;
use App\Models\MaterialCategory;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class RawMaterialsSeeder extends Seeder
{
    public function run()
    {
        $foam = MaterialCategory::firstWhere('name', 'Foam');
        $leather = MaterialCategory::firstWhere('name', 'Leather');
        $metal = MaterialCategory::firstWhere('name', 'Metal');
        $chemicals = MaterialCategory::firstWhere('name', 'Chemicals');

        $karachiFoam = Supplier::firstWhere('company_name', 'Karachi Foam Industries');
        $lahoreLeather = Supplier::firstWhere('company_name', 'Lahore Leather Works');
        $faslabadSteel = Supplier::firstWhere('company_name', 'Faisalabad Steel Traders');
        $multanChemicals = Supplier::firstWhere('company_name', 'Multan Chemicals Ltd');

        $items = [
            [
                'category_id' => $foam->id,
                'supplier_id' => $karachiFoam->id,
                'material_code' => 'MAT-000001',
                'name' => 'High Density Seat Foam',
                'unit' => 'KG',
                'minimum_stock' => 50,
                'current_stock' => 450,
                'purchase_price' => 120.00,
                'average_cost' => 125.50,
                'attributes' => json_encode(['density' => '35', 'thickness' => '50mm', 'quality' => 'Premium']),
                'description' => 'High-density polyurethane foam for motorcycle seats',
                'status' => 'active',
            ],
            [
                'category_id' => $foam->id,
                'supplier_id' => $karachiFoam->id,
                'material_code' => 'MAT-000002',
                'name' => 'Medium Density Foam',
                'unit' => 'KG',
                'minimum_stock' => 100,
                'current_stock' => 320,
                'purchase_price' => 85.00,
                'average_cost' => 88.00,
                'attributes' => json_encode(['density' => '25', 'thickness' => '40mm', 'quality' => 'Standard']),
                'description' => 'Medium-density foam for cushioning',
                'status' => 'active',
            ],
            [
                'category_id' => $leather->id,
                'supplier_id' => $lahoreLeather->id,
                'material_code' => 'MAT-000003',
                'name' => 'Black Synthetic Leather',
                'unit' => 'MTR',
                'minimum_stock' => 30,
                'current_stock' => 180,
                'purchase_price' => 450.00,
                'average_cost' => 465.00,
                'attributes' => json_encode(['type' => 'PVC', 'color' => 'Black', 'thickness' => '1.2mm', 'quality' => 'Premium']),
                'description' => 'Premium black synthetic leather for seat covers',
                'status' => 'active',
            ],
            [
                'category_id' => $leather->id,
                'supplier_id' => $lahoreLeather->id,
                'material_code' => 'MAT-000004',
                'name' => 'Brown Synthetic Leather',
                'unit' => 'MTR',
                'minimum_stock' => 20,
                'current_stock' => 85,
                'purchase_price' => 420.00,
                'average_cost' => 430.00,
                'attributes' => json_encode(['type' => 'PU', 'color' => 'Brown', 'thickness' => '1.0mm', 'quality' => 'Standard']),
                'description' => 'Brown synthetic leather for vintage style seats',
                'status' => 'active',
            ],
            [
                'category_id' => $metal->id,
                'supplier_id' => $faslabadSteel->id,
                'material_code' => 'MAT-000005',
                'name' => 'Steel Frame 16 Gauge',
                'unit' => 'PCS',
                'minimum_stock' => 25,
                'current_stock' => 120,
                'purchase_price' => 350.00,
                'average_cost' => 360.00,
                'attributes' => json_encode(['type' => 'Steel Frame', 'gauge' => '16', 'finish' => 'Powder Coated']),
                'description' => 'Steel frame for motorcycle seat base',
                'status' => 'active',
            ],
            [
                'category_id' => $metal->id,
                'supplier_id' => $faslabadSteel->id,
                'material_code' => 'MAT-000006',
                'name' => 'Steel Springs',
                'unit' => 'PCS',
                'minimum_stock' => 50,
                'current_stock' => 200,
                'purchase_price' => 25.00,
                'average_cost' => 26.50,
                'attributes' => json_encode(['type' => 'Coil Spring', 'gauge' => '12', 'finish' => 'Galvanized']),
                'description' => 'Steel coil springs for seat suspension',
                'status' => 'active',
            ],
            [
                'category_id' => $chemicals->id,
                'supplier_id' => $multanChemicals->id,
                'material_code' => 'MAT-000007',
                'name' => 'Contact Adhesive',
                'unit' => 'LTR',
                'minimum_stock' => 15,
                'current_stock' => 45,
                'purchase_price' => 850.00,
                'average_cost' => 875.00,
                'attributes' => json_encode(['type' => 'Contact Adhesive', 'brand' => 'Sika', 'quantity' => '5L']),
                'description' => 'Industrial contact adhesive for bonding foam and leather',
                'status' => 'active',
            ],
            [
                'category_id' => $chemicals->id,
                'supplier_id' => $multanChemicals->id,
                'material_code' => 'MAT-000008',
                'name' => 'Foam Bonding Agent',
                'unit' => 'LTR',
                'minimum_stock' => 10,
                'current_stock' => 30,
                'purchase_price' => 650.00,
                'average_cost' => 670.00,
                'attributes' => json_encode(['type' => 'Foam Glue', 'brand' => 'Henkel', 'quantity' => '5L']),
                'description' => 'Specialized adhesive for foam bonding',
                'status' => 'active',
            ],
        ];

        foreach ($items as $it) {
            RawMaterial::updateOrCreate(['material_code' => $it['material_code']], $it);
        }
    }
}
