<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('inventory/stock/index', 'Stock', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Inventory'],
                ['label' => 'Stock'],
            ]);
    }
}
