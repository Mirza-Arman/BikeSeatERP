@extends('layouts.modern')

@section('title', 'Dashboard')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}! Here's what's happening today.</p>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-ui.stats-card
            title="Total Suppliers"
            value="{{ App\Models\Supplier::count() }}"
            icon="truck"
            color="blue"
            trend="up"
            trendValue="12% from last month"
        />
        <x-ui.stats-card
            title="Raw Materials"
            value="{{ App\Models\RawMaterial::count() }}"
            icon="cube"
            color="emerald"
            trend="up"
            trendValue="8% from last month"
        />
        <x-ui.stats-card
            title="Inventory Value"
            value="PKR {{ number_format(App\Models\InventoryItem::sum('total_value') ?? 0) }}"
            icon="archive-box"
            color="orange"
            trend="up"
            trendValue="5% from last month"
        />
        <x-ui.stats-card
            title="Total Customers"
            value="{{ App\Models\Customer::count() }}"
            icon="users"
            color="purple"
            trend="up"
            trendValue="15% from last month"
        />
    </div>

    {{-- Additional KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-ui.stats-card
            title="Production Today"
            value="{{ App\Models\ProductionOrder::whereDate('created_at', today())->count() }}"
            icon="cog"
            color="cyan"
            trend="up"
            trendValue="3% from yesterday"
        />
        <x-ui.stats-card
            title="Pending Purchases"
            value="{{ App\Models\PurchaseOrder::where('status', 'pending')->count() }}"
            icon="shopping-cart"
            color="orange"
            trend="down"
            trendValue="2% from last week"
        />
        <x-ui.stats-card
            title="Recent Orders"
            value="{{ App\Models\SalesOrder::whereDate('created_at', '>=', now()->subDays(7))->count() }}"
            icon="clipboard-document-list"
            color="blue"
            trend="up"
            trendValue="20% from last week"
        />
        <x-ui.stats-card
            title="Monthly Revenue"
            value="PKR {{ number_format(App\Models\SalesOrder::whereMonth('created_at', now()->month)->sum('total_amount') ?? 0) }}"
            icon="currency-dollar"
            color="emerald"
            trend="up"
            trendValue="18% from last month"
        />
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Monthly Purchases Chart --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Monthly Purchases</h3>
                <x-ui.button variant="outline" size="sm">View Details</x-ui.button>
            </div>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                <div class="text-center">
                    <x-heroicon-o-chart-bar class="h-12 w-12 text-gray-400 mx-auto mb-2" />
                    <p class="text-gray-500 text-sm">Chart placeholder</p>
                    <p class="text-gray-400 text-xs">Chart.js integration pending</p>
                </div>
            </div>
        </x-ui.card>

        {{-- Production Chart --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Production Overview</h3>
                <x-ui.button variant="outline" size="sm">View Details</x-ui.button>
            </div>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                <div class="text-center">
                    <x-heroicon-o-chart-bar class="h-12 w-12 text-gray-400 mx-auto mb-2" />
                    <p class="text-gray-500 text-sm">Chart placeholder</p>
                    <p class="text-gray-400 text-xs">Chart.js integration pending</p>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- More Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Inventory Chart --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Inventory Levels</h3>
            </div>
            <div class="h-48 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                <div class="text-center">
                    <x-heroicon-chart-pie class="h-10 w-10 text-gray-400 mx-auto mb-2" />
                    <p class="text-gray-500 text-sm">Chart placeholder</p>
                </div>
            </div>
        </x-ui.card>

        {{-- Supplier Payments Chart --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Supplier Payments</h3>
            </div>
            <div class="h-48 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                <div class="text-center">
                    <x-heroicon-o-chart-bar class="h-10 w-10 text-gray-400 mx-auto mb-2" />
                    <p class="text-gray-500 text-sm">Chart placeholder</p>
                </div>
            </div>
        </x-ui.card>

        {{-- Sales Chart --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Sales Trends</h3>
            </div>
            <div class="h-48 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                <div class="text-center">
                    <x-heroicon-chart-line class="h-10 w-10 text-gray-400 mx-auto mb-2" />
                    <p class="text-gray-500 text-sm">Chart placeholder</p>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Recent Activity Table --}}
    <x-ui.card>
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
            <x-ui.button variant="outline" size="sm">View All</x-ui.button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Activity</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">New customer added</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ auth()->user()->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ now()->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-ui.badge variant="success">Completed</x-ui.badge>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Purchase order created</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ auth()->user()->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ now()->subHour()->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-ui.badge variant="warning">Pending</x-ui.badge>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Production order completed</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ auth()->user()->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ now()->subHours(2)->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-ui.badge variant="success">Completed</x-ui.badge>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
