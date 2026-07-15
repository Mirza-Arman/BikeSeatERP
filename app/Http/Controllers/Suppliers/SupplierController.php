<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('suppliers/index', 'Suppliers', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Supplier Management'],
                ['label' => 'Suppliers'],
            ]);
    }
}
