<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DeliveryRecordController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('customers/delivery-records/index', 'Delivery Records', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Customers'],
                ['label' => 'Delivery Records'],
            ]);
    }
}
