<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\PurchaseOrder;
use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    public function getStatistics(): array
    {
        $todayProduction = ProductionOrder::whereDate('production_date', Carbon::today())->count();
        $lowStockCount = RawMaterial::whereColumn('current_stock', '<=', 'minimum_stock')->count()
            + Product::whereColumn('current_stock', '<=', 'minimum_stock')->count();

        return [
            [
                'title' => 'Total Employees',
                'value' => (string) Employee::count(),
                'icon' => 'fas fa-users',
                'class' => 'bg-primary-gradient',
            ],
            [
                'title' => 'Total Suppliers',
                'value' => (string) Supplier::count(),
                'icon' => 'fas fa-truck-loading',
                'class' => 'bg-success-gradient',
            ],
            [
                'title' => 'Total Customers',
                'value' => (string) Customer::count(),
                'icon' => 'fas fa-user-friends',
                'class' => 'bg-info-gradient',
            ],
            [
                'title' => 'Raw Materials',
                'value' => (string) RawMaterial::count(),
                'icon' => 'fas fa-cubes',
                'class' => 'bg-warning-gradient',
            ],
            [
                'title' => 'Products',
                'value' => (string) Product::count(),
                'icon' => 'fas fa-chair',
                'class' => 'bg-purple-gradient',
            ],
            [
                'title' => 'Production Today',
                'value' => (string) $todayProduction,
                'icon' => 'fas fa-industry',
                'class' => 'bg-teal-gradient',
            ],
            [
                'title' => 'Low Stock',
                'value' => (string) $lowStockCount,
                'icon' => 'fas fa-exclamation-triangle',
                'class' => 'bg-danger-gradient',
            ],
            [
                'title' => 'Purchase Orders',
                'value' => (string) PurchaseOrder::count(),
                'icon' => 'fas fa-file-invoice',
                'class' => 'bg-dark-gradient',
            ],
            [
                'title' => 'Customer Orders',
                'value' => (string) CustomerOrder::count(),
                'icon' => 'fas fa-shopping-cart',
                'class' => 'bg-primary-gradient',
            ],
        ];
    }

    public function getProductionOverview(): array
    {
        $statusCounts = ProductionOrder::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'today' => ProductionOrder::whereDate('production_date', Carbon::today())->count(),
            'pending' => $statusCounts['pending'] ?? 0,
            'in_progress' => $statusCounts['in_progress'] ?? 0,
            'completed' => $statusCounts['completed'] ?? 0,
        ];
    }

    public function getRecentActivities(): array
    {
        $activities = collect()
            ->merge($this->productionActivities())
            ->merge($this->purchaseActivities())
            ->merge($this->customerOrderActivities())
            ->merge($this->stockActivities())
            ->sortByDesc('date')
            ->values()
            ->take(4)
            ->map(function (array $activity) {
                return [
                    'title' => $activity['title'],
                    'description' => $activity['description'],
                    'time' => $activity['date']->diffForHumans(),
                ];
            })
            ->toArray();

        if ($activities === []) {
            return [
                [
                    'title' => 'No recent activity available',
                    'description' => 'When the first orders, stock changes, or production events happen, this list will update automatically.',
                    'time' => '-',
                ],
            ];
        }

        return $activities;
    }

    protected function productionActivities(): Collection
    {
        return ProductionOrder::latest()
            ->limit(4)
            ->get()
            ->map(function (ProductionOrder $order) {
                return [
                    'title' => 'Production order '.$order->order_no.' is '.str_replace('_', ' ', $order->status),
                    'description' => $order->product?->product_name ?: 'Production order updated.',
                    'date' => $order->updated_at,
                ];
            });
    }

    protected function purchaseActivities(): Collection
    {
        return PurchaseOrder::latest()
            ->limit(4)
            ->get()
            ->map(function (PurchaseOrder $order) {
                return [
                    'title' => 'Purchase order '.$order->invoice_no.' created',
                    'description' => 'Supplier: '.optional($order->supplier)->company_name,
                    'date' => $order->updated_at,
                ];
            });
    }

    protected function customerOrderActivities(): Collection
    {
        return CustomerOrder::latest()
            ->limit(4)
            ->get()
            ->map(function (CustomerOrder $order) {
                return [
                    'title' => 'Customer order '.$order->invoice_no.' placed',
                    'description' => 'Customer: '.optional($order->customer)->customer_name,
                    'date' => $order->updated_at,
                ];
            });
    }

    protected function stockActivities(): Collection
    {
        return RawMaterial::where('current_stock', '<=', 'minimum_stock')
            ->latest()
            ->limit(4)
            ->get()
            ->map(function (RawMaterial $material) {
                return [
                    'title' => 'Low stock alert for '.$material->name,
                    'description' => 'Current: '.number_format($material->current_stock, 2).' '.$material->unit,
                    'date' => $material->updated_at,
                ];
            });
    }
}
