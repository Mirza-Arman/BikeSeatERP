<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('customers/index', 'Customers', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Customers'],
                ['label' => 'Customers'],
            ]);
    }
}
