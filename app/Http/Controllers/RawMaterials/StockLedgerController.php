<?php

namespace App\Http\Controllers\RawMaterials;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class StockLedgerController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('raw-materials/stock-ledger/index', 'Stock Ledger', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Raw Material'],
                ['label' => 'Stock Ledger'],
            ]);
    }
}
