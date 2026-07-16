<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchases\PurchaseOrderRequest;
use App\Models\PurchaseOrder;
use App\Models\RawMaterial;
use App\Models\Supplier;
use App\Services\Inventory\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    public function __construct(protected StockService $stockService)
    {
    }

    public function index(Request $request): View
    {
        $query = PurchaseOrder::query()->with('supplier');

        if ($request->filled('search')) {
            $query->where('invoice_no', 'like', '%' . $request->search . '%');
        }

        $purchaseOrders = $query->latest()->paginate(15);

        return view('purchases.index', compact('purchaseOrders'));
    }

    public function create(): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        $rawMaterials = RawMaterial::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'rawMaterials'));
    }

    public function store(PurchaseOrderRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $purchaseOrder = DB::transaction(function () use ($data, $request): PurchaseOrder {
            $subtotal = 0;
            foreach ($request->input('items', []) as $item) {
                $subtotal += ((float) $item['quantity'] * (float) $item['unit_price']);
            }

            $tax = isset($data['tax']) ? (float) $data['tax'] : (isset($data['tax_amount']) ? (float) $data['tax_amount'] : 0);
            $grandTotal = $subtotal + $tax;
            $paidAmount = isset($data['paid_amount']) ? (float) $data['paid_amount'] : 0;
            $remainingAmount = $grandTotal - $paidAmount;

            if (empty($data['invoice_no'])) {
                $lastOrder = PurchaseOrder::withTrashed()->orderBy('id', 'desc')->first();
                $lastId = $lastOrder ? $lastOrder->id : 0;
                $data['invoice_no'] = 'PO-' . date('Ymd') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
            }

            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'invoice_no' => $data['invoice_no'],
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'status' => $data['status'] ?? 'pending',
            ]);

            foreach ($request->input('items', []) as $item) {
                $quantity = (float) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];
                $purchaseOrder->items()->create([
                    'raw_material_id' => $item['raw_material_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $quantity * $unitPrice,
                ]);
            }

            foreach ($purchaseOrder->items as $item) {
                $this->stockService->addRawMaterialStock(
                    $item->raw_material_id,
                    (float) $item->quantity,
                    PurchaseOrder::class,
                    $purchaseOrder->id,
                    'Purchase order received'
                );
            }

            $purchaseOrder->supplier()->update(['balance' => $purchaseOrder->supplier->balance + $grandTotal]);

            return $purchaseOrder;
        });

        return redirect()->route('erp.suppliers.purchase-orders.index')->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        return view('purchases.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        $rawMaterials = RawMaterial::orderBy('name')->get();

        return view('purchases.edit', compact('purchaseOrder', 'suppliers', 'rawMaterials'));
    }

    public function update(PurchaseOrderRequest $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request, $purchaseOrder): void {
            $subtotal = 0;
            foreach ($request->input('items', []) as $item) {
                $subtotal += ((float) $item['quantity'] * (float) $item['unit_price']);
            }

            $tax = isset($data['tax']) ? (float) $data['tax'] : (isset($data['tax_amount']) ? (float) $data['tax_amount'] : 0);
            $grandTotal = $subtotal + $tax;
            $paidAmount = isset($data['paid_amount']) ? (float) $data['paid_amount'] : 0;
            $remainingAmount = $grandTotal - $paidAmount;

            $purchaseOrder->update([
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'invoice_no' => $data['invoice_no'],
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'status' => $data['status'] ?? 'pending',
            ]);

            $purchaseOrder->items()->delete();

            foreach ($request->input('items', []) as $item) {
                $quantity = (float) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];
                $purchaseOrder->items()->create([
                    'raw_material_id' => $item['raw_material_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $quantity * $unitPrice,
                ]);
            }
        });

        return redirect()->route('erp.suppliers.purchase-orders.index')->with('success', 'Purchase order updated successfully.');
    }

    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->delete();

        return redirect()->route('erp.suppliers.purchase-orders.index')->with('success', 'Purchase order deleted successfully.');
    }
}
