<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class FinishedGoodController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('inventory/finished-goods/index', 'Finished Goods', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Inventory'],
                ['label' => 'Finished Goods'],
            ]);
    }
}
