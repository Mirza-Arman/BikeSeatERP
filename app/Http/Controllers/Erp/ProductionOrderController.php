<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Services\Inventory\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductionOrderController extends Controller
{
    public function __construct(protected StockService $stockService)
    {
    }

    public function index(Request $request): View
    {
        $query = ProductionOrder::query()->with('product');

        if ($request->filled('search')) {
            $query->where('order_no', 'like', '%' . $request->search . '%');
        }

        $productionOrders = $query->latest()->paginate(15);

        return view('production.orders.index', compact('productionOrders'));
    }

    public function create(): View
    {
        $products = Product::where('status', 'active')->orderBy('product_name')->get();

        return view('production.orders.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_to_produce' => 'required|numeric|min:0.01',
            'production_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $formula = $product->productionFormula;

        if (!$formula) {
            return redirect()->back()->with('error', 'No production formula found for this product. Please create a formula first.');
        }

        // Check stock availability
        $stockIssues = [];
        foreach ($formula->items as $item) {
            $requiredQuantity = $item->quantity_required * $request->quantity_to_produce;
            $availableStock = $item->rawMaterial->current_stock;
            
            if ($availableStock < $requiredQuantity) {
                $stockIssues[] = [
                    'material' => $item->rawMaterial->name,
                    'required' => $requiredQuantity,
                    'available' => $availableStock,
                    'shortage' => $requiredQuantity - $availableStock,
                ];
            }
        }

        if (!empty($stockIssues)) {
            return redirect()->back()->with('error', 'Insufficient stock for production.')->with('stock_issues', $stockIssues);
        }

        // Auto-generate order number
        $lastOrder = ProductionOrder::withTrashed()->orderBy('id', 'desc')->first();
        $lastId = $lastOrder ? $lastOrder->id : 0;
        $orderNo = 'PRO-' . date('Ymd') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        $productionOrder = ProductionOrder::create([
            'order_no' => $orderNo,
            'product_id' => $request->product_id,
            'formula_id' => $formula->id,
            'quantity_to_produce' => $request->quantity_to_produce,
            'production_date' => $request->production_date,
            'status' => 'pending',
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('erp.production.orders.show', $productionOrder)->with('success', 'Production order created successfully.');
    }

    public function show(ProductionOrder $productionOrder): View
    {
        $productionOrder->load(['product', 'formula.items.rawMaterial', 'workers.employee']);
        
        return view('production.orders.show', compact('productionOrder'));
    }

    public function updateStatus(Request $request, ProductionOrder $productionOrder): RedirectResponse
    {
        $request->validate(['status' => 'required|in:pending,in_progress,completed']);
        
        $productionOrder->update(['status' => $request->status]);

        if ($productionOrder->status === 'completed' && $productionOrder->formula_id) {
            DB::transaction(function () use ($productionOrder) {
                $formula = $productionOrder->formula;
                
                // Subtract raw materials
                foreach ($formula->items as $item) {
                    $requiredQuantity = $item->quantity_required * $productionOrder->quantity_to_produce;
                    
                    $this->stockService->removeRawMaterialStock(
                        $item->raw_material_id,
                        $requiredQuantity,
                        ProductionOrder::class,
                        $productionOrder->id,
                        'Raw materials consumed for production'
                    );
                }

                // Add finished goods
                $this->stockService->addFinishedGoodsStock(
                    $productionOrder->product_id,
                    (float) $productionOrder->quantity_to_produce,
                    ProductionOrder::class,
                    $productionOrder->id,
                    'Finished goods produced'
                );
            });
        }

        return redirect()->route('erp.production.orders.show', $productionOrder)->with('success', 'Production order status updated successfully.');
    }

    public function destroy(ProductionOrder $productionOrder): RedirectResponse
    {
        $productionOrder->delete();

        return redirect()->route('erp.production.orders.index')->with('success', 'Production order deleted successfully.');
    }

    public function assignWorkers(Request $request, ProductionOrder $productionOrder): RedirectResponse
    {
        $request->validate([
            'workers' => 'required|array',
            'workers.*.employee_id' => 'required|exists:employees,id',
            'workers.*.assigned_work' => 'nullable|string',
            'workers.*.completed_quantity' => 'nullable|numeric|min:0',
        ]);

        $productionOrder->workers()->delete();

        foreach ($request->workers as $worker) {
            $productionOrder->workers()->create($worker);
        }

        return redirect()->route('erp.production.orders.show', $productionOrder)->with('success', 'Workers assigned successfully.');
    }
}
