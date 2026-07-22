<?php

namespace Database\Seeders;

use App\Models\MaterialCategory;
use App\Models\MaterialCategoryAttribute;
use Illuminate\Database\Seeder;

class MaterialCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Foam',
                'description' => 'Foam materials for seat padding and cushioning',
                'attributes' => [
                    ['attribute_name' => 'density', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 1],
                    ['attribute_name' => 'thickness', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 2],
                    ['attribute_name' => 'quality', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Leather',
                'description' => 'Leather and synthetic leather for seat covers',
                'attributes' => [
                    ['attribute_name' => 'type', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 1],
                    ['attribute_name' => 'color', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 2],
                    ['attribute_name' => 'thickness', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 3],
                    ['attribute_name' => 'quality', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 4],
                ],
            ],
            [
                'name' => 'Metal',
                'description' => 'Metal frames, springs, and hardware',
                'attributes' => [
                    ['attribute_name' => 'type', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 1],
                    ['attribute_name' => 'gauge', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 2],
                    ['attribute_name' => 'finish', 'attribute_type' => 'text', 'is_required' => false, 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Chemicals',
                'description' => 'Adhesives, bonding agents, and chemicals',
                'attributes' => [
                    ['attribute_name' => 'type', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 1],
                    ['attribute_name' => 'brand', 'attribute_type' => 'text', 'is_required' => false, 'sort_order' => 2],
                    ['attribute_name' => 'quantity', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Fabric',
                'description' => 'Fabric and textile materials',
                'attributes' => [
                    ['attribute_name' => 'type', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 1],
                    ['attribute_name' => 'color', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 2],
                    ['attribute_name' => 'pattern', 'attribute_type' => 'text', 'is_required' => false, 'sort_order' => 3],
                    ['attribute_name' => 'quality', 'attribute_type' => 'text', 'is_required' => true, 'sort_order' => 4],
                ],
            ],
        ];

        foreach ($categories as $c) {
            $category = MaterialCategory::updateOrCreate(['name' => $c['name']], [
                'description' => $c['description'] ?? null,
            ]);

            if (isset($c['attributes'])) {
                foreach ($c['attributes'] as $attr) {
                    MaterialCategoryAttribute::updateOrCreate(
                        [
                            'category_id' => $category->id,
                            'attribute_name' => $attr['attribute_name'],
                        ],
                        $attr
                    );
                }
            }
        }
    }
}
