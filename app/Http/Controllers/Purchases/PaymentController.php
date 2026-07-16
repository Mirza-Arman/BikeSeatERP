<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchases\PaymentRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $query = PurchasePayment::with(['purchaseOrder', 'supplier', 'createdBy']);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);
        $suppliers = Supplier::all();
        $statistics = $this->paymentService->getPaymentStatistics();

        return view('purchases.payments.index', compact('payments', 'suppliers', 'statistics'));
    }

    public function create(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier');

        return view('purchases.payments.create', compact('purchaseOrder'));
    }

    public function store(PaymentRequest $request)
    {
        try {
            $payment = $this->paymentService->recordPayment(
                $request->validated(),
                auth()->id()
            );

            return redirect()
                ->route('purchases.purchase-orders.show', $payment->purchase_order_id)
                ->with('success', 'Payment recorded successfully.');
        } catch (\RuntimeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(PurchasePayment $payment)
    {
        try {
            $this->paymentService->deletePayment($payment);

            return redirect()
                ->back()
                ->with('success', 'Payment deleted successfully.');
        } catch (\RuntimeException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
