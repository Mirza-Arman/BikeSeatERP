<?php

namespace App\Services\Inventory;

use App\Models\RawMaterial;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function addRawMaterialStock(int $rawMaterialId, float $quantity, string $referenceType, int $referenceId, string $remarks = ''): void
    {
        DB::transaction(function () use ($rawMaterialId, $quantity, $referenceType, $referenceId, $remarks): void {
            $rawMaterial = RawMaterial::lockForUpdate()->findOrFail($rawMaterialId);
            $newBalance = (float) $rawMaterial->current_stock + $quantity;

            $rawMaterial->current_stock = $newBalance;
            $rawMaterial->save();

            StockTransaction::create([
                'raw_material_id' => $rawMaterialId,
                'transaction_type' => 'purchase',
                'quantity' => $quantity,
                'balance_after' => $newBalance,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'remarks' => $remarks,
            ]);
        });
    }

    public function removeRawMaterialStock(int $rawMaterialId, float $quantity, string $referenceType, int $referenceId, string $remarks = ''): void
    {
        DB::transaction(function () use ($rawMaterialId, $quantity, $referenceType, $referenceId, $remarks): void {
            $rawMaterial = RawMaterial::lockForUpdate()->findOrFail($rawMaterialId);

            if ((float) $rawMaterial->current_stock < $quantity) {
                throw new \RuntimeException('Insufficient stock for production.');
            }

            $newBalance = (float) $rawMaterial->current_stock - $quantity;
            $rawMaterial->current_stock = $newBalance;
            $rawMaterial->save();

            StockTransaction::create([
                'raw_material_id' => $rawMaterialId,
                'transaction_type' => 'production',
                'quantity' => -$quantity,
                'balance_after' => $newBalance,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'remarks' => $remarks,
            ]);
        });
    }

    public function addFinishedGoodsStock(int $productId, float $quantity, string $referenceType, int $referenceId, string $remarks = ''): void
    {
        DB::transaction(function () use ($productId, $quantity, $referenceType, $referenceId, $remarks): void {
            $product = \App\Models\Product::lockForUpdate()->findOrFail($productId);
            $newBalance = (float) $product->current_stock + $quantity;

            $product->current_stock = $newBalance;
            $product->save();

            \App\Models\FinishedGoodsTransaction::create([
                'product_id' => $productId,
                'transaction_type' => 'production',
                'quantity' => $quantity,
                'balance_after' => $newBalance,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);
        });
    }

    public function removeFinishedGoodsStock(int $productId, float $quantity, string $referenceType, int $referenceId, string $remarks = ''): void
    {
        DB::transaction(function () use ($productId, $quantity, $referenceType, $referenceId, $remarks): void {
            $product = \App\Models\Product::lockForUpdate()->findOrFail($productId);

            if ((float) $product->current_stock < $quantity) {
                throw new \RuntimeException('Insufficient finished goods stock.');
            }

            $newBalance = (float) $product->current_stock - $quantity;
            $product->current_stock = $newBalance;
            $product->save();

            \App\Models\FinishedGoodsTransaction::create([
                'product_id' => $productId,
                'transaction_type' => 'sale',
                'quantity' => -$quantity,
                'balance_after' => $newBalance,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);
        });
    }
}
