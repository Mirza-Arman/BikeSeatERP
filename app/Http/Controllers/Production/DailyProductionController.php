<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DailyProductionController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('production/daily/index', 'Daily Production', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Production'],
                ['label' => 'Daily Production'],
            ]);
    }
}
