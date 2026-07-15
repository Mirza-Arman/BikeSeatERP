<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return $this->moduleView('reports/index', 'Reports', [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Reports'],
            ]);
    }
}
