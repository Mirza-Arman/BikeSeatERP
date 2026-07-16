<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function index(): View
    {
        return view('dashboard.index', [
            'pageTitle' => 'Dashboard',
            'breadcrumbs' => [
                ['label' => 'Dashboard'],
            ],
            'cards' => $this->dashboardService->getStatistics(),
            'overview' => $this->dashboardService->getProductionOverview(),
            'activities' => $this->dashboardService->getRecentActivities(),
        ]);
    }
}
