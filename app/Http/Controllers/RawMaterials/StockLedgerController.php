<?php

namespace App\Http\Controllers\RawMaterials;

use App\Http\Controllers\Controller;
use App\Models\StockTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StockLedgerController extends Controller
{
    public function index(Request $request): View
    {
        $query = StockTransaction::with('rawMaterial');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('rawMaterial', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(15);

        return view('raw-materials.stock-ledger.index', compact('transactions'));
    }
}
