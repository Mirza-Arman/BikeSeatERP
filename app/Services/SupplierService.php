<?php

namespace App\Services;

use App\Models\RawMaterial;
use App\Models\Supplier;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;

class SupplierService
{
    public function generateSupplierCode(): string
    {
        $lastSupplier = Supplier::withTrashed()->orderBy('id', 'desc')->first();

        if (! $lastSupplier) {
            return 'SUP-000001';
        }

        $lastCode = $lastSupplier->supplier_code ?? 'SUP-000000';
        $numericPart = (int) str_replace('SUP-', '', $lastCode);
        $newNumber = $numericPart + 1;

        return 'SUP-'.str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function getSupplierDashboard(Supplier $supplier): array
    {
        $totalPurchases = $supplier->purchaseOrders()->count();
        $totalAmountPurchased = $supplier->purchaseOrders()->sum('grand_total');
        $totalPaid = $supplier->payments()->sum('amount');
        $remainingBalance = $supplier->balance;
        $lastPurchaseDate = $supplier->purchaseOrders()->latest('purchase_date')->value('purchase_date');
        $materialsSupplied = $supplier->rawMaterials()->count();

        $recentPurchases = $supplier->purchaseOrders()
            ->with('items.rawMaterial')
            ->latest('purchase_date')
            ->limit(5)
            ->get();

        return [
            'total_purchases' => $totalPurchases,
            'total_amount_purchased' => $totalAmountPurchased,
            'total_paid' => $totalPaid,
            'remaining_balance' => $remainingBalance,
            'last_purchase_date' => $lastPurchaseDate,
            'materials_supplied' => $materialsSupplied,
            'recent_purchases' => $recentPurchases,
        ];
    }

    public function getSupplierLedger(Supplier $supplier): array
    {
        $ledger = [];

        $purchaseOrders = $supplier->purchaseOrders()
            ->orderBy('purchase_date')
            ->get();

        foreach ($purchaseOrders as $po) {
            $ledger[] = [
                'date' => $po->purchase_date,
                'reference_number' => $po->purchase_number,
                'material_purchased' => $po->items->pluck('rawMaterial.name')->implode(', '),
                'debit' => $po->grand_total,
                'credit' => 0,
                'balance' => 0,
                'remarks' => 'Purchase Order',
                'type' => 'purchase',
            ];
        }

        $payments = $supplier->payments()
            ->orderBy('payment_date')
            ->get();

        foreach ($payments as $payment) {
            $ledger[] = [
                'date' => $payment->payment_date,
                'reference_number' => $payment->reference_number ?? 'PAY-'.$payment->id,
                'material_purchased' => '-',
                'debit' => 0,
                'credit' => $payment->amount,
                'balance' => 0,
                'remarks' => $payment->remarks ?? 'Payment',
                'type' => 'payment',
            ];
        }

        usort($ledger, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        $runningBalance = 0;
        foreach ($ledger as &$entry) {
            $runningBalance += $entry['debit'] - $entry['credit'];
            $entry['balance'] = $runningBalance;
        }

        return $ledger;
    }

    public function getMaterialsSupplied(Supplier $supplier): array
    {
        $materials = $supplier->rawMaterials()
            ->with('category')
            ->get()
            ->map(function ($material) {
                $lastPurchaseItem = $material->purchaseItems()
                    ->whereHas('purchaseOrder', function ($query) use ($supplier) {
                        $query->where('supplier_id', $supplier->id);
                    })
                    ->latest()
                    ->first();

                return [
                    'material' => $material->name,
                    'model' => $material->attributes['model'] ?? '-',
                    'quality' => $material->attributes['quality'] ?? '-',
                    'unit' => $material->unit,
                    'last_price' => $lastPurchaseItem ? $lastPurchaseItem->unit_price : 0,
                    'last_purchase' => $lastPurchaseItem ? $lastPurchaseItem->purchaseOrder->purchase_date : '-',
                ];
            });

        return $materials->toArray();
    }

    public function recordSupplierPayment(array $data, int $userId): void
    {
        DB::transaction(function () use ($data, $userId) {
            $supplier = Supplier::findOrFail($data['supplier_id']);

            $supplier->update([
                'balance' => $supplier->balance - $data['amount'],
            ]);

            app(PaymentService::class)->recordPayment([
                'purchase_order_id' => null,
                'supplier_id' => $data['supplier_id'],
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'] ?? null,
                'bank' => $data['bank'] ?? null,
                'cheque_number' => $data['cheque_number'] ?? null,
                'payment_date' => $data['payment_date'],
                'remarks' => $data['remarks'] ?? 'Direct supplier payment',
            ], $userId);
        });
    }

    public function getSupplierStatistics(): array
    {
        return [
            'total_suppliers' => Supplier::count(),
            'active_suppliers' => Supplier::where('status', 'active')->count(),
            'total_outstanding' => Supplier::where('balance', '>', 0)->sum('balance'),
        ];
    }
}
