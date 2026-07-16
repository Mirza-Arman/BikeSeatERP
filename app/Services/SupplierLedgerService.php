<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchasePayment;
use App\Models\Supplier;

class SupplierLedgerService
{
    public function getSupplierLedger(int $supplierId): array
    {
        $supplier = Supplier::findOrFail($supplierId);

        $purchases = PurchaseOrder::where('supplier_id', $supplierId)
            ->orderBy('purchase_date')
            ->get()
            ->map(function ($purchase) {
                return [
                    'date' => $purchase->purchase_date,
                    'type' => 'debit',
                    'reference' => $purchase->purchase_number,
                    'description' => "Purchase Order #{$purchase->purchase_number}",
                    'debit' => $purchase->grand_total,
                    'credit' => 0,
                    'balance' => 0,
                ];
            });

        $payments = PurchasePayment::where('supplier_id', $supplierId)
            ->orderBy('payment_date')
            ->get()
            ->map(function ($payment) {
                return [
                    'date' => $payment->payment_date,
                    'type' => 'credit',
                    'reference' => $payment->reference_number ?? $payment->id,
                    'description' => "Payment - {$payment->payment_method}",
                    'debit' => 0,
                    'credit' => $payment->amount,
                    'balance' => 0,
                ];
            });

        $transactions = $purchases->concat($payments)
            ->sortBy('date')
            ->values();

        $runningBalance = 0;
        $transactions = $transactions->map(function ($transaction) use (&$runningBalance) {
            $runningBalance += $transaction['debit'] - $transaction['credit'];
            $transaction['balance'] = $runningBalance;

            return $transaction;
        });

        return [
            'supplier' => $supplier,
            'transactions' => $transactions,
            'total_purchases' => $purchases->sum('debit'),
            'total_payments' => $payments->sum('credit'),
            'outstanding_balance' => $runningBalance,
            'last_purchase_date' => $purchases->last()?->date ?? null,
        ];
    }

    public function updateSupplierBalance(int $supplierId, float $amount, string $type): void
    {
        $supplier = Supplier::findOrFail($supplierId);

        if ($type === 'debit') {
            $supplier->balance += $amount;
        } elseif ($type === 'credit') {
            $supplier->balance -= $amount;
        }

        $supplier->save();
    }

    public function getSupplierSummary(int $supplierId): array
    {
        $supplier = Supplier::findOrFail($supplierId);

        $totalPurchases = PurchaseOrder::where('supplier_id', $supplierId)->sum('grand_total');
        $totalPayments = PurchasePayment::where('supplier_id', $supplierId)->sum('amount');
        $outstandingBalance = $totalPurchases - $totalPayments;
        $lastPurchaseDate = PurchaseOrder::where('supplier_id', $supplierId)->max('purchase_date');
        $totalPaymentsCount = PurchasePayment::where('supplier_id', $supplierId)->count();

        return [
            'supplier' => $supplier,
            'total_purchases' => $totalPurchases,
            'outstanding_balance' => $outstandingBalance,
            'last_purchase_date' => $lastPurchaseDate,
            'total_payments' => $totalPayments,
            'total_payments_count' => $totalPaymentsCount,
        ];
    }

    public function getAllSuppliersOutstanding(): array
    {
        return Supplier::with('purchaseOrders')
            ->get()
            ->map(function ($supplier) {
                $totalPurchases = $supplier->purchaseOrders->sum('grand_total');
                $totalPayments = PurchasePayment::where('supplier_id', $supplier->id)->sum('amount');
                $outstanding = $totalPurchases - $totalPayments;

                return [
                    'id' => $supplier->id,
                    'company_name' => $supplier->company_name,
                    'supplier_code' => $supplier->supplier_code,
                    'total_purchases' => $totalPurchases,
                    'total_payments' => $totalPayments,
                    'outstanding_balance' => $outstanding,
                ];
            })
            ->filter(fn ($supplier) => $supplier['outstanding_balance'] > 0)
            ->sortByDesc('outstanding_balance')
            ->values()
            ->toArray();
    }
}
