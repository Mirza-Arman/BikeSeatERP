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
        $todayPurchases = PurchaseOrder::whereDate('purchase_date', Carbon::today())->sum('grand_total');
        $pendingPurchaseOrders = PurchaseOrder::where('status', 'pending')->count();
        $supplierOutstanding = PurchaseOrder::where('remaining_amount', '>', 0)->sum('remaining_amount');
        $overduePurchaseOrders = PurchaseOrder::where('status', '!=', 'completed')
            ->where('expected_delivery', '<', Carbon::today())
            ->count();

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
                'title' => 'Today\'s Purchases',
                'value' => number_format($todayPurchases, 2),
                'icon' => 'fas fa-shopping-bag',
                'class' => 'bg-primary-gradient',
            ],
            [
                'title' => 'Pending POs',
                'value' => (string) $pendingPurchaseOrders,
                'icon' => 'fas fa-clock',
                'class' => 'bg-warning-gradient',
            ],
            [
                'title' => 'Supplier Outstanding',
                'value' => number_format($supplierOutstanding, 2),
                'icon' => 'fas fa-money-bill-wave',
                'class' => 'bg-danger-gradient',
            ],
            [
                'title' => 'Overdue POs',
                'value' => (string) $overduePurchaseOrders,
                'icon' => 'fas fa-exclamation-circle',
                'class' => 'bg-secondary-gradient',
            ],
            [
                'title' => 'Customer Orders',
                'value' => (string) CustomerOrder::count(),
                'icon' => 'fas fa-shopping-cart',
                'class' => 'bg-info-gradient',
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

    public function getNotifications(): array
    {
        $lowStockMaterials = RawMaterial::where('current_stock', '<=', 'minimum_stock')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (RawMaterial $material) {
                return [
                    'type' => 'warning',
                    'message' => "Low stock: {$material->name} ({$material->current_stock} {$material->unit})",
                    'url' => route('erp.raw-materials.show', $material),
                ];
            });

        $overduePurchaseOrders = PurchaseOrder::where('status', '!=', 'completed')
            ->where('expected_delivery', '<', Carbon::today())
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (PurchaseOrder $order) {
                return [
                    'type' => 'danger',
                    'message' => "Overdue PO: {$order->purchase_number} from {$order->supplier->company_name}",
                    'url' => route('purchases.purchase-orders.show', $order),
                ];
            });

        return $lowStockMaterials->concat($overduePurchaseOrders)->toArray();
    }
}
