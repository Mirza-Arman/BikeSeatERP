<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class PurchaseOrderController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('suppliers/purchase-orders/index', 'Purchase Orders', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Supplier Management'],
                ['label' => 'Purchase Orders'],
            ]);
    }
}
