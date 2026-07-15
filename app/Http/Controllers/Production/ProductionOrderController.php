<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ProductionOrderController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('production/orders/index', 'Production Orders', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Production'],
                ['label' => 'Production Orders'],
            ]);
    }
}
