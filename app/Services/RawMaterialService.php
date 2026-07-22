<?php

namespace App\Services;

use App\Models\RawMaterial;
use Illuminate\Support\Facades\DB;

class RawMaterialService
{
    public function generateMaterialCode(): string
    {
        $lastMaterial = RawMaterial::withTrashed()->orderBy('id', 'desc')->first();

        if (! $lastMaterial) {
            return 'MAT-000001';
        }

        $lastCode = $lastMaterial->material_code ?? 'MAT-000000';
        $numericPart = (int) str_replace('MAT-', '', $lastCode);
        $newNumber = $numericPart + 1;

        return 'MAT-'.str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function createRawMaterial(array $data, int $userId): RawMaterial
    {
        return DB::transaction(function () use ($data, $userId) {
            $attributes = [];
            if (isset($data['attributes']) && is_array($data['attributes'])) {
                foreach ($data['attributes'] as $key => $value) {
                    if (! empty($value)) {
                        $attributes[$key] = $value;
                    }
                }
            }

            $material = RawMaterial::create([
                'category_id' => $data['category_id'],
                'supplier_id' => $data['supplier_id'] ?? null,
                'material_code' => $this->generateMaterialCode(),
                'name' => $data['name'],
                'attributes' => $attributes,
                'unit' => $data['unit'],
                'minimum_stock' => $data['minimum_stock'] ?? 0,
                'current_stock' => $data['current_stock'] ?? 0,
                'cost_per_unit' => $data['cost_per_unit'] ?? 0,
                'purchase_price' => $data['purchase_price'] ?? 0,
                'average_cost' => $data['purchase_price'] ?? 0,
                'description' => $data['description'] ?? null,
                'status' => $data['status'] ?? 'active',
            ]);

            return $material;
        });
    }

    public function updateRawMaterial(RawMaterial $material, array $data): RawMaterial
    {
        return DB::transaction(function () use ($material, $data) {
            $attributes = [];
            if (isset($data['attributes']) && is_array($data['attributes'])) {
                foreach ($data['attributes'] as $key => $value) {
                    if (! empty($value)) {
                        $attributes[$key] = $value;
                    }
                }
            }

            $material->update([
                'category_id' => $data['category_id'] ?? $material->category_id,
                'supplier_id' => $data['supplier_id'] ?? $material->supplier_id,
                'name' => $data['name'] ?? $material->name,
                'attributes' => $attributes,
                'unit' => $data['unit'] ?? $material->unit,
                'minimum_stock' => $data['minimum_stock'] ?? $material->minimum_stock,
                'cost_per_unit' => $data['cost_per_unit'] ?? $material->cost_per_unit,
                'purchase_price' => $data['purchase_price'] ?? $material->purchase_price,
                'average_cost' => $data['average_cost'] ?? $material->average_cost,
                'description' => $data['description'] ?? $material->description,
                'status' => $data['status'] ?? $material->status,
            ]);

            return $material->load('category', 'supplier');
        });
    }

    public function updateAverageCost(RawMaterial $material, float $newPrice): void
    {
        $currentStock = $material->current_stock;
        $currentAverageCost = $material->average_cost;

        if ($currentStock > 0) {
            $newAverageCost = (($currentAverageCost * $currentStock) + ($newPrice)) / ($currentStock + 1);
            $material->update(['average_cost' => $newAverageCost]);
        } else {
            $material->update(['average_cost' => $newPrice]);
        }
    }

    public function getMaterialDetail(RawMaterial $material): array
    {
        $material->load('category', 'supplier', 'stockTransactions', 'purchaseItems.purchaseOrder');

        $supplierHistory = $material->purchaseItems()
            ->with('purchaseOrder.supplier')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->purchaseOrder->purchase_date,
                    'supplier' => $item->purchaseOrder->supplier->company_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->total,
                ];
            });

        $stockMovement = $material->stockTransactions()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($transaction) {
                return [
                    'date' => $transaction->created_at,
                    'type' => $transaction->type,
                    'quantity' => $transaction->quantity,
                    'previous_quantity' => $transaction->previous_quantity,
                    'new_quantity' => $transaction->new_quantity,
                    'reference' => $transaction->reference,
                ];
            });

        $priceHistory = $material->purchaseItems()
            ->with('purchaseOrder')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->purchaseOrder->purchase_date,
                    'price' => $item->unit_price,
                    'supplier' => $item->purchaseOrder->supplier->company_name,
                ];
            });

        return [
            'material' => $material,
            'supplier_history' => $supplierHistory,
            'stock_movement' => $stockMovement,
            'price_history' => $priceHistory,
        ];
    }

    public function getStockSummary(): array
    {
        $totalCategories = \App\Models\MaterialCategory::count();
        $totalMaterials = RawMaterial::count();
        $lowStockItems = RawMaterial::whereColumn('current_stock', '<=', 'minimum_stock')->count();
        $outOfStockItems = RawMaterial::where('current_stock', 0)->count();
        $inventoryValue = RawMaterial::selectRaw('SUM(current_stock * average_cost) as total_value')->value('total_value') ?? 0;

        return [
            'total_categories' => $totalCategories,
            'total_materials' => $totalMaterials,
            'low_stock_items' => $lowStockItems,
            'out_of_stock_items' => $outOfStockItems,
            'inventory_value' => $inventoryValue,
        ];
    }

    public function checkDuplicateMaterial(array $data): bool
    {
        $query = RawMaterial::where('name', $data['name'])
            ->where('category_id', $data['category_id']);

        if (isset($data['supplier_id'])) {
            $query->where('supplier_id', $data['supplier_id']);
        }

        if (isset($data['attributes']) && is_array($data['attributes'])) {
            foreach ($data['attributes'] as $key => $value) {
                if (! empty($value)) {
                    $query->whereJsonContains('attributes->'.$key, $value);
                }
            }
        }

        return $query->exists();
    }
}
