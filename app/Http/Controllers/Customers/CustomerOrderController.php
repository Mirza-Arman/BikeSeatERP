<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CustomerOrderController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('customers/orders/index', 'Customer Orders', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Customers'],
                ['label' => 'Customer Orders'],
            ]);
    }
}
