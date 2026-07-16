<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchases\GoodsReceiptRequest;
use App\Models\PurchaseOrder;
use App\Services\PurchaseService;

class GoodsReceiptController extends Controller
{
    private PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function create(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'completed') {
            return redirect()
                ->back()
                ->with('error', 'This purchase order is already completed.');
        }

        $purchaseOrder->load(['items.rawMaterial', 'supplier']);

        return view('purchases.goods-receipts.create', compact('purchaseOrder'));
    }

    public function store(GoodsReceiptRequest $request, PurchaseOrder $purchaseOrder)
    {
        try {
            $this->purchaseService->processGoodsReceipt(
                $purchaseOrder,
                $request->items,
                $request->remarks ?? '',
                auth()->id()
            );

            return redirect()
                ->route('purchases.purchase-orders.show', $purchaseOrder)
                ->with('success', 'Goods received successfully. Inventory updated.');
        } catch (\RuntimeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
