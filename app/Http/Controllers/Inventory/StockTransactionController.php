<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class StockTransactionController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('inventory/transactions/index', 'Stock Transactions', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Inventory'],
                ['label' => 'Stock Transactions'],
            ]);
    }
}
