<?php

namespace Database\Seeders;

use App\Models\ProductionFormula;
use App\Models\ProductionFormulaItem;
use App\Models\Product;
use App\Models\RawMaterial;
use Illuminate\Database\Seeder;

class ProductionFormulasSeeder extends Seeder
{
    public function run()
    {
        $product = Product::firstWhere('product_code', 'PRD-001');
        $foam = RawMaterial::firstWhere('material_code', 'RM-FOAM-001');
        $leather = RawMaterial::firstWhere('material_code', 'RM-LEATH-001');

        if (! $product || ! $foam || ! $leather) {
            return;
        }

        $formula = ProductionFormula::updateOrCreate([
            'product_id' => $product->id,
        ], [
            'version' => 'v1',
            'description' => $product->product_name . ' base formula',
        ]);

        ProductionFormulaItem::updateOrCreate([
            'formula_id' => $formula->id,
            'raw_material_id' => $foam->id,
        ], [
            'quantity_required' => 1,
            'unit' => $foam->unit,
        ]);

        ProductionFormulaItem::updateOrCreate([
            'formula_id' => $formula->id,
            'raw_material_id' => $leather->id,
        ], [
            'quantity_required' => 1,
            'unit' => $leather->unit,
        ]);
    }
}
