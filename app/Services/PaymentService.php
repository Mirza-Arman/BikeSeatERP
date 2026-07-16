<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function recordPayment(array $data, int $userId): PurchasePayment
    {
        return DB::transaction(function () use ($data, $userId) {
            $purchaseOrder = PurchaseOrder::findOrFail($data['purchase_order_id']);

            if ($data['amount'] > $purchaseOrder->remaining_amount) {
                throw new \RuntimeException('Cannot pay more than remaining balance.');
            }

            $payment = PurchasePayment::create([
                'purchase_order_id' => $data['purchase_order_id'],
                'supplier_id' => $data['supplier_id'],
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'] ?? null,
                'bank' => $data['bank'] ?? null,
                'cheque_number' => $data['cheque_number'] ?? null,
                'payment_date' => $data['payment_date'],
                'remarks' => $data['remarks'] ?? null,
                'created_by' => $userId,
            ]);

            $totalPaid = $purchaseOrder->payments()->sum('amount');
            $remainingAmount = $purchaseOrder->grand_total - $totalPaid;

            $purchaseOrder->update([
                'paid_amount' => $totalPaid,
                'remaining_amount' => $remainingAmount,
                'payment_status' => $remainingAmount <= 0 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid'),
            ]);

            app(SupplierLedgerService::class)->updateSupplierBalance(
                $data['supplier_id'],
                $data['amount'],
                'credit'
            );

            return $payment->load('purchaseOrder', 'supplier');
        });
    }

    public function deletePayment(PurchasePayment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $purchaseOrder = $payment->purchaseOrder;
            $supplierId = $payment->supplier_id;
            $amount = $payment->amount;

            $payment->delete();

            $totalPaid = $purchaseOrder->payments()->sum('amount');
            $remainingAmount = $purchaseOrder->grand_total - $totalPaid;

            $purchaseOrder->update([
                'paid_amount' => $totalPaid,
                'remaining_amount' => $remainingAmount,
                'payment_status' => $remainingAmount <= 0 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid'),
            ]);

            app(SupplierLedgerService::class)->updateSupplierBalance(
                $supplierId,
                $amount,
                'debit'
            );
        });
    }

    public function getPaymentStatistics(): array
    {
        return [
            'total_payments_today' => PurchasePayment::whereDate('payment_date', now()->toDateString())->sum('amount'),
            'total_payments_month' => PurchasePayment::whereBetween('payment_date', [now()->startOfMonth()->toDateString(), now()->toDateString()])->sum('amount'),
            'pending_payments' => PurchaseOrder::where('remaining_amount', '>', 0)->count(),
            'overdue_payments' => PurchaseOrder::where('remaining_amount', '>', 0)
                ->where('expected_delivery', '<', now()->toDateString())
                ->count(),
        ];
    }
}
