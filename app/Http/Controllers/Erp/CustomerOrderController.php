<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerOrderRequest;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Product;
use App\Services\Inventory\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index(Request $request): View
    {
        $query = CustomerOrder::query()->with('customer');

        if ($request->filled('search')) {
            $query->where('invoice_no', 'like', '%'.$request->search.'%');
        }

        $customerOrders = $query->latest()->paginate(15);

        return view('sales.orders.index', compact('customerOrders'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('customer_name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('sales.orders.create', compact('customers', 'products'));
    }

    public function store(CustomerOrderRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $customerOrder = DB::transaction(function () use ($data): CustomerOrder {
            $customerOrder = CustomerOrder::create([
                'invoice_no' => $data['invoice_no'] ?? null,
                'customer_id' => $data['customer_id'],
                'order_date' => $data['order_date'],
                'status' => 'pending',
            ]);

            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $quantity = (float) $item['quantity'];
                $price = (float) $item['price'];
                $total = $quantity * $price;
                $subtotal += $total;

                $customerOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                ]);
            }

            $customerOrder->update([
                'grand_total' => $subtotal,
                'paid_amount' => 0,
                'remaining_amount' => $subtotal,
            ]);

            return $customerOrder;
        });

        return redirect()->route('erp.customers.orders.index')->with('success', 'Customer order created successfully.');
    }

    public function show(CustomerOrder $customerOrder): View
    {
        $customerOrder->load(['customer', 'items.product']);

        return view('sales.orders.show', compact('customerOrder'));
    }

    public function updateStatus(Request $request, CustomerOrder $customerOrder): RedirectResponse
    {
        $status = $request->input('status', 'completed');

        if ($status === 'completed' && $customerOrder->status === 'completed') {
            return redirect()->route('erp.customers.orders.show', $customerOrder)
                ->with('error', 'This customer order has already been completed.');
        }

        DB::transaction(function () use ($customerOrder, $status): void {
            $customerOrder->load('items');
            $customerOrder->update(['status' => $status]);

            if ($status === 'completed') {
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
        });

        return redirect()->route('erp.customers.orders.index')->with('success', 'Customer order updated successfully.');
    }

    public function destroy(CustomerOrder $customerOrder): RedirectResponse
    {
        $customerOrder->delete();

        return redirect()->route('erp.customers.orders.index')->with('success', 'Customer order deleted successfully.');
    }
}
