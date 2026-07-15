<?php

namespace App\Http\Controllers\RawMaterials;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class StockAdjustmentController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('raw-materials/stock-adjustments/index', 'Stock Adjustments', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Raw Material'],
                ['label' => 'Stock Adjustments'],
            ]);
    }
}
