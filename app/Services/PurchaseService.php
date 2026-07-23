<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\RawMaterial;
use App\Services\Inventory\StockService;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function generatePurchaseNumber(): string
    {
        $lastOrder = PurchaseOrder::withTrashed()->orderBy('id', 'desc')->first();

        if (! $lastOrder) {
            return 'PO-000001';
        }

        $lastNumber = $lastOrder->purchase_number ?? 'PO-000000';
        $numericPart = (int) str_replace('PO-', '', $lastNumber);
        $newNumber = $numericPart + 1;

        return 'PO-'.str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function createPurchaseOrder(array $data, int $userId): PurchaseOrder
    {
        return DB::transaction(function () use ($data, $userId) {
            $purchaseOrder = PurchaseOrder::create([
                'purchase_number' => $this->generatePurchaseNumber(),
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'expected_delivery' => $data['expected_delivery'] ?? null,
                'invoice_no' => $data['invoice_no'] ?? null,
                'invoice_number' => $data['invoice_number'] ?? null,
                'subtotal' => 0,
                'discount' => $data['discount'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'tax_amount' => $data['tax_amount'] ?? 0,
                'transport_cost' => $data['transport_cost'] ?? 0,
                'other_cost' => $data['other_cost'] ?? 0,
                'grand_total' => 0,
                'paid_amount' => $data['paid_amount'] ?? 0,
                'remaining_amount' => 0,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => $data['notes'] ?? null,
                'created_by' => $userId,
            ]);

            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $rawMaterial = RawMaterial::findOrFail($item['raw_material_id']);
                $total = $item['quantity'] * $item['unit_price'];
                $subtotal += $total;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'quantity' => $item['quantity'],
                    'unit' => $rawMaterial->unit,
                    'unit_price' => $item['unit_price'],
                    'total' => $total,
                    'received_quantity' => 0,
                ]);
            }

            $discount = $data['discount'] ?? 0;
            $taxAmount = $data['tax_amount'] ?? 0;
            $transportCost = $data['transport_cost'] ?? 0;
            $otherCost = $data['other_cost'] ?? 0;
            $grandTotal = $subtotal - $discount + $taxAmount + $transportCost + $otherCost;
            $paidAmount = $data['paid_amount'] ?? 0;
            $remainingAmount = $grandTotal - $paidAmount;

            $purchaseOrder->update([
                'subtotal' => $subtotal,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'payment_status' => $remainingAmount <= 0 ? 'paid' : ($paidAmount > 0 ? 'partial' : 'unpaid'),
            ]);

            app(SupplierLedgerService::class)->updateSupplierBalance(
                (int) $data['supplier_id'],
                $grandTotal,
                'debit'
            );

            if ($paidAmount > 0) {
                app(PaymentService::class)->recordPayment([
                    'purchase_order_id' => $purchaseOrder->id,
                    'supplier_id' => $data['supplier_id'],
                    'amount' => $paidAmount,
                    'payment_method' => $data['payment_method'] ?? 'cash',
                    'reference_number' => $data['reference_number'] ?? null,
                    'bank' => $data['bank'] ?? null,
                    'cheque_number' => $data['cheque_number'] ?? null,
                    'payment_date' => $data['purchase_date'],
                    'remarks' => 'Initial payment with purchase order',
                ], $userId);
            }

            return $purchaseOrder->load('items.rawMaterial', 'supplier');
        });
    }

    public function updatePurchaseOrder(PurchaseOrder $purchaseOrder, array $data): PurchaseOrder
    {
        if ($purchaseOrder->status === 'completed') {
            throw new \RuntimeException('Cannot edit completed purchase orders.');
        }

        return DB::transaction(function () use ($purchaseOrder, $data) {
            $purchaseOrder->items()->delete();

            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $rawMaterial = RawMaterial::findOrFail($item['raw_material_id']);
                $total = $item['quantity'] * $item['unit_price'];
                $subtotal += $total;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'quantity' => $item['quantity'],
                    'unit' => $rawMaterial->unit,
                    'unit_price' => $item['unit_price'],
                    'total' => $total,
                    'received_quantity' => 0,
                ]);
            }

            $discount = $data['discount'] ?? $purchaseOrder->discount;
            $taxAmount = $data['tax_amount'] ?? $purchaseOrder->tax_amount;
            $transportCost = $data['transport_cost'] ?? $purchaseOrder->transport_cost;
            $otherCost = $data['other_cost'] ?? $purchaseOrder->other_cost;
            $grandTotal = $subtotal - $discount + $taxAmount + $transportCost + $otherCost;
            $totalPaid = $purchaseOrder->payments()->sum('amount');
            $remainingAmount = $grandTotal - $totalPaid;

            $purchaseOrder->update([
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'expected_delivery' => $data['expected_delivery'] ?? null,
                'invoice_no' => $data['invoice_no'] ?? null,
                'invoice_number' => $data['invoice_number'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $data['tax'] ?? $purchaseOrder->tax,
                'tax_amount' => $taxAmount,
                'transport_cost' => $transportCost,
                'other_cost' => $otherCost,
                'grand_total' => $grandTotal,
                'remaining_amount' => $remainingAmount,
                'payment_status' => $remainingAmount <= 0 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid'),
                'notes' => $data['notes'] ?? null,
            ]);

            return $purchaseOrder->load('items.rawMaterial', 'supplier');
        });
    }

    public function deletePurchaseOrder(PurchaseOrder $purchaseOrder): void
    {
        if ($purchaseOrder->status === 'completed') {
            throw new \RuntimeException('Cannot delete completed purchase orders.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            $purchaseOrder->payments()->delete();
            $purchaseOrder->items()->delete();
            $purchaseOrder->delete();
        });
    }

    public function processGoodsReceipt(PurchaseOrder $purchaseOrder, array $items, string $remarks, int $userId): void
    {
        DB::transaction(function () use ($purchaseOrder, $items, $remarks, $userId) {
            foreach ($items as $itemData) {
                $purchaseOrderItem = PurchaseOrderItem::findOrFail($itemData['purchase_order_item_id']);
                $quantityToReceive = $itemData['received_quantity'];

                if ($quantityToReceive > ($purchaseOrderItem->quantity - $purchaseOrderItem->received_quantity)) {
                    throw new \RuntimeException('Cannot receive more than ordered quantity.');
                }

                $purchaseOrderItem->received_quantity += $quantityToReceive;
                $purchaseOrderItem->save();

                if ($quantityToReceive > 0) {
                    $stockService = app(StockService::class);
                    $previousQuantity = $purchaseOrderItem->rawMaterial->current_stock;
                    $stockService->addRawMaterialStock(
                        $purchaseOrderItem->raw_material_id,
                        $quantityToReceive,
                        PurchaseOrder::class,
                        $purchaseOrder->id,
                        "Goods receipt for PO #{$purchaseOrder->purchase_number}"
                    );

                    $stockTransaction = $purchaseOrderItem->rawMaterial->stockTransactions()
                        ->where('reference_type', PurchaseOrder::class)
                        ->where('reference_id', $purchaseOrder->id)
                        ->latest()
                        ->first();

                    if ($stockTransaction) {
                        $stockTransaction->update([
                            'previous_quantity' => $previousQuantity,
                            'supplier_name' => $purchaseOrder->supplier->company_name,
                            'user_id' => $userId,
                        ]);
                    }
                }
            }

            $allReceived = $purchaseOrder->items()->whereColumn('received_quantity', '>=', 'quantity')->count() === $purchaseOrder->items()->count();
            $partiallyReceived = $purchaseOrder->items()->where('received_quantity', '>', 0)->exists();

            $purchaseOrder->update([
                'status' => $allReceived ? 'completed' : ($partiallyReceived ? 'partial' : 'pending'),
            ]);

            $receiptNumber = 'GR-'.date('Ymd').'-'.str_pad($purchaseOrder->goodsReceipts()->count() + 1, 4, '0', STR_PAD_LEFT);

            $purchaseOrder->goodsReceipts()->create([
                'receipt_number' => $receiptNumber,
                'received_date' => now()->toDateString(),
                'remarks' => $remarks,
                'created_by' => $userId,
            ]);
        });
    }

    public function getPurchaseStatistics(): array
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();

        return [
            'today_purchases' => PurchaseOrder::whereDate('purchase_date', $today)->sum('grand_total'),
            'monthly_purchases' => PurchaseOrder::whereBetween('purchase_date', [$monthStart, now()->toDateString()])->sum('grand_total'),
            'pending_orders' => PurchaseOrder::where('status', 'pending')->count(),
            'completed_orders' => PurchaseOrder::where('status', 'completed')->count(),
            'supplier_outstanding' => PurchaseOrder::where('remaining_amount', '>', 0)->sum('remaining_amount'),
            'overdue_orders' => PurchaseOrder::where('status', '!=', 'completed')
                ->where('expected_delivery', '<', now()->toDateString())
                ->count(),
        ];
    }
}
