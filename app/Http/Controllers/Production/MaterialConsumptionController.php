<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class MaterialConsumptionController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('production/material-consumption/index', 'Material Consumption', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Production'],
                ['label' => 'Material Consumption'],
            ]);
    }
}
