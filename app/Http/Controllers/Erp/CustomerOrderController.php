<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Product;
use App\Services\Inventory\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    public function __construct(protected StockService $stockService)
    {
    }

    public function index(Request $request): View
    {
        $query = CustomerOrder::query()->with('customer');

        if ($request->filled('search')) {
            $query->where('order_no', 'like', '%' . $request->search . '%');
        }

        $customerOrders = $query->latest()->paginate(15);

        return view('sales.orders.index', compact('customerOrders'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('sales.orders.create', compact('customers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $customerOrder = CustomerOrder::create([
            'order_no' => $request->input('order_no'),
            'customer_id' => $request->input('customer_id'),
            'order_date' => $request->input('order_date'),
            'status' => 'pending',
        ]);

        foreach ($request->input('items', []) as $item) {
            $customerOrder->items()->create($item);
        }

        return redirect()->route('erp.customer-orders.index')->with('success', 'Customer order created successfully.');
    }

    public function show(CustomerOrder $customerOrder): View
    {
        return view('sales.orders.show', compact('customerOrder'));
    }

    public function updateStatus(Request $request, CustomerOrder $customerOrder): RedirectResponse
    {
        $customerOrder->update(['status' => $request->input('status', 'completed')]);

        if ($customerOrder->status === 'completed') {
            foreach ($customerOrder->items as $item) {
                $this->stockService->removeFinishedGoodsStock(
                    $item->product_id,
                    (float) $item->quantity,
                    CustomerOrder::class,
                    $customerOrder->id,
                    'Customer order fulfilled'
                );
            }
        }

        return redirect()->route('erp.customer-orders.index')->with('success', 'Customer order updated successfully.');
    }

    public function destroy(CustomerOrder $customerOrder): RedirectResponse
    {
        $customerOrder->delete();

        return redirect()->route('erp.customer-orders.index')->with('success', 'Customer order deleted successfully.');
    }
}
