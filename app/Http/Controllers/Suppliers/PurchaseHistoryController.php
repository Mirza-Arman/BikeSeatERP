<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PurchaseHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = PurchaseOrder::with('supplier');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($q) use ($search) {
                      $q->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        $purchaseOrders = $query->orderBy('purchase_date', 'desc')->paginate(15);

        return view('suppliers.purchase-history.index', compact('purchaseOrders'));
    }
}
