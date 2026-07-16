<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchases\PurchaseOrderRequest;
use App\Models\PurchaseOrder;
use App\Models\RawMaterial;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    private PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'items.rawMaterial']);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                    ->orWhere('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        $purchaseOrders = $query->orderBy('purchase_date', 'desc')->paginate(15);
        $suppliers = Supplier::all();
        $statistics = $this->purchaseService->getPurchaseStatistics();

        return view('purchases.purchase-orders.index', compact('purchaseOrders', 'suppliers', 'statistics'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $rawMaterials = RawMaterial::with('category')->get();

        return view('purchases.purchase-orders.create', compact('suppliers', 'rawMaterials'));
    }

    public function store(PurchaseOrderRequest $request)
    {
        $purchaseOrder = $this->purchaseService->createPurchaseOrder(
            $request->validated(),
            auth()->id()
        );

        return redirect()
            ->route('purchases.purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.rawMaterial', 'payments', 'goodsReceipts.createdBy', 'createdBy']);

        return view('purchases.purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'completed') {
            return redirect()
                ->back()
                ->with('error', 'Cannot edit completed purchase orders.');
        }

        $purchaseOrder->load('items.rawMaterial');
        $suppliers = Supplier::all();
        $rawMaterials = RawMaterial::with('category')->get();

        return view('purchases.purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'rawMaterials'));
    }

    public function update(PurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder = $this->purchaseService->updatePurchaseOrder(
            $purchaseOrder,
            $request->validated()
        );

        return redirect()
            ->route('purchases.purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase order updated successfully.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        try {
            $this->purchaseService->deletePurchaseOrder($purchaseOrder);

            return redirect()
                ->route('purchases.purchase-orders.index')
                ->with('success', 'Purchase order deleted successfully.');
        } catch (\RuntimeException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
