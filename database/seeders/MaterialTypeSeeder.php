<?php

namespace Database\Seeders;

use App\Models\MaterialType;
use App\Models\MaterialTypeAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materialTypes = [
            [
                'name' => 'Iron Plate',
                'code' => 'IRON_PLATE',
                'description' => 'Metal plates for motorcycle seat frames',
                'attributes' => [
                    ['name' => 'Model', 'type' => 'select', 'options' => ['70cc', '125cc', '150cc'], 'is_required' => true, 'sort_order' => 1],
                    ['name' => 'Weight', 'type' => 'number', 'is_required' => true, 'sort_order' => 2],
                    ['name' => 'Thickness', 'type' => 'number', 'is_required' => false, 'sort_order' => 3],
                    ['name' => 'Supplier Code', 'type' => 'text', 'is_required' => false, 'sort_order' => 4],
                ],
            ],
            [
                'name' => 'Plastic Plate',
                'code' => 'PLASTIC_PLATE',
                'description' => 'Plastic components for seat structure',
                'attributes' => [
                    ['name' => 'Color', 'type' => 'select', 'options' => ['White', 'Black', 'Grey'], 'is_required' => true, 'sort_order' => 1],
                    ['name' => 'Model', 'type' => 'select', 'options' => ['70cc', '125cc'], 'is_required' => true, 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'Seat Cover',
                'code' => 'SEAT_COVER',
                'description' => 'Fabric or leather covers for seats',
                'attributes' => [
                    ['name' => 'Brand', 'type' => 'text', 'is_required' => true, 'sort_order' => 1],
                    ['name' => 'Model', 'type' => 'text', 'is_required' => true, 'sort_order' => 2],
                    ['name' => 'Color', 'type' => 'text', 'is_required' => true, 'sort_order' => 3],
                    ['name' => 'Texture', 'type' => 'select', 'options' => ['Smooth', 'Textured', 'Leather'], 'is_required' => false, 'sort_order' => 4],
                ],
            ],
            [
                'name' => 'Gola',
                'code' => 'GOLA',
                'description' => 'Rubber gola for seat edges',
                'attributes' => [
                    ['name' => 'Model', 'type' => 'text', 'is_required' => true, 'sort_order' => 1],
                    ['name' => 'Quality', 'type' => 'select', 'options' => ['Premium', 'Standard', 'Economy'], 'is_required' => true, 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'ISO Chemical',
                'code' => 'ISO_CHEMICAL',
                'description' => 'ISO chemical for foam production',
                'attributes' => [
                    ['name' => 'Type', 'type' => 'select', 'options' => ['ISO'], 'is_required' => true, 'sort_order' => 1],
                    ['name' => 'Density', 'type' => 'number', 'is_required' => true, 'sort_order' => 2],
                    ['name' => 'Purity', 'type' => 'number', 'is_required' => true, 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'POLY Chemical',
                'code' => 'POLY_CHEMICAL',
                'description' => 'POLY chemical for foam production',
                'attributes' => [
                    ['name' => 'Type', 'type' => 'select', 'options' => ['POLY'], 'is_required' => true, 'sort_order' => 1],
                    ['name' => 'Density', 'type' => 'number', 'is_required' => true, 'sort_order' => 2],
                    ['name' => 'Purity', 'type' => 'number', 'is_required' => true, 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Viper Rubber',
                'code' => 'VIPER_RUBBER',
                'description' => 'Viper rubber for seat components',
                'attributes' => [
                    ['name' => 'Quality', 'type' => 'select', 'options' => ['Premium', 'Standard'], 'is_required' => true, 'sort_order' => 1],
                    ['name' => 'Grade', 'type' => 'select', 'options' => ['A', 'B', 'C'], 'is_required' => true, 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'Seat Belt',
                'code' => 'SEAT_BELT',
                'description' => 'Seat belts for motorcycle seats',
                'attributes' => [
                    ['name' => 'Quality', 'type' => 'select', 'options' => ['Premium', 'Standard'], 'is_required' => true, 'sort_order' => 1],
                    ['name' => 'Model', 'type' => 'text', 'is_required' => true, 'sort_order' => 2],
                    ['name' => 'Length', 'type' => 'number', 'is_required' => true, 'sort_order' => 3],
                ],
            ],
        ];

        foreach ($materialTypes as $materialTypeData) {
            $attributes = $materialTypeData['attributes'];
            unset($materialTypeData['attributes']);

            $materialType = MaterialType::create($materialTypeData);

            foreach ($attributes as $attributeData) {
                MaterialTypeAttribute::create([
                    'material_type_id' => $materialType->id,
                    ...$attributeData,
                ]);
            }
        }
    }
}
