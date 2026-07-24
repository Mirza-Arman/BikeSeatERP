<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DailyProductionController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProductionOrder::with(['product', 'workers']);

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $dailyProductions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('production.daily.index', compact('dailyProductions'));
    }
}
