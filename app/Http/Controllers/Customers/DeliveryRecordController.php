<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DeliveryRecordController extends Controller
{
    public function index(Request $request): View
    {
        $query = CustomerOrder::with(['customer', 'product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('customer_name', 'like', "%{$search}%");
                  });
            });
        }

        $deliveries = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('customers.delivery-records.index', compact('deliveries'));
    }
}
