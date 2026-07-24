<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MaterialConsumptionController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProductionOrder::with(['product', 'workers']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($q) use ($search) {
                      $q->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        $consumptions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('production.material-consumption.index', compact('consumptions'));
    }
}
