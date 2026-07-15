<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class PurchaseHistoryController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('suppliers/purchase-history/index', 'Purchase History', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Supplier Management'],
                ['label' => 'Purchase History'],
            ]);
    }
}
