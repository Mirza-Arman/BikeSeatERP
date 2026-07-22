@extends('layouts.modern')

@section('title', 'Dashboard')

@section('content')
    <x-ui.page-header 
        title="Dashboard" 
        subtitle="Welcome back, {{ auth()->user()->name }}! Here's what's happening today."
    />

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @foreach($cards as $card)
            <x-ui-stats-card 
                :title="$card['title']" 
                :value="$card['value']" 
                :icon="'o-' . str_replace('fas fa-', '', $card['icon'])"
                :color="match($card['class']) {
                    'bg-primary-gradient' => 'blue',
                    'bg-success-gradient' => 'emerald',
                    'bg-warning-gradient' => 'orange',
                    'bg-danger-gradient' => 'red',
                    'bg-purple-gradient' => 'purple',
                    'bg-teal-gradient' => 'cyan',
                    'bg-info-gradient' => 'blue',
                    'bg-secondary-gradient' => 'gray',
                    default => 'blue'
                }
            />
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Production Overview --}}
        <div class="lg:col-span-2">
            <x-ui-card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Production Overview</h3>
                    <x-heroicon-o-chart-bar class="h-5 w-5 text-gray-400" />
                </div>

                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Today</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $overview['today'] }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Pending</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $overview['pending'] }}</p>
                    </div>
                    <div class="bg-emerald-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Completed</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $overview['completed'] }}</p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                @php
                    $productionTotal = max(1, $overview['pending'] + $overview['in_progress'] + $overview['completed']);
                    $pendingPercent = $overview['pending'] > 0 ? min(100, ($overview['pending'] / $productionTotal) * 100) : 0;
                    $inProgressPercent = $overview['in_progress'] > 0 ? min(100, ($overview['in_progress'] / $productionTotal) * 100) : 0;
                    $completedPercent = $overview['completed'] > 0 ? min(100, ($overview['completed'] / $productionTotal) * 100) : 0;
                @endphp
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Pending</span>
                        <span class="font-medium">{{ number_format($pendingPercent, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pendingPercent }}%"></div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">In Progress</span>
                        <span class="font-medium">{{ number_format($inProgressPercent, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ $inProgressPercent }}%"></div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Completed</span>
                        <span class="font-medium">{{ number_format($completedPercent, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $completedPercent }}%"></div>
                    </div>
                </div>
            </x-ui-card>
        </div>

        {{-- Recent Activities --}}
        <div>
            <x-ui-card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
                    <x-heroicon-o-clock class="h-5 w-5 text-gray-400" />
                </div>

                <div class="space-y-4">
                    @forelse($activities as $activity)
                        <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <x-heroicon-o-bell class="h-4 w-4 text-blue-600" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <x-heroicon-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                            <p class="text-gray-500">No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </x-ui-card>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Quick Access --}}
        <x-ui-card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Quick Access</h3>
                <x-heroicon-bolt class="h-5 w-5 text-gray-400" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('erp.production.orders.index') }}" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-primary-500 hover:bg-primary-50 transition-all group">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                        <x-heroicon-cog class="h-5 w-5 text-blue-600" />
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Production</p>
                        <p class="text-xs text-gray-500">Manage orders</p>
                    </div>
                </a>

                <a href="{{ route('erp.inventory.stock.index') }}" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-primary-500 hover:bg-primary-50 transition-all group">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                        <x-heroicon-archive-box class="h-5 w-5 text-emerald-600" />
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Inventory</p>
                        <p class="text-xs text-gray-500">View stock</p>
                    </div>
                </a>

                <a href="{{ route('erp.suppliers.index') }}" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-primary-500 hover:bg-primary-50 transition-all group">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                        <x-heroicon-truck class="h-5 w-5 text-orange-600" />
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Suppliers</p>
                        <p class="text-xs text-gray-500">Manage vendors</p>
                    </div>
                </a>

                <a href="{{ route('erp.customers.index') }}" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-primary-500 hover:bg-primary-50 transition-all group">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <x-heroicon-users class="h-5 w-5 text-purple-600" />
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Customers</p>
                        <p class="text-xs text-gray-500">View clients</p>
                    </div>
                </a>
            </div>
        </x-ui-card>

        {{-- Chart Placeholder --}}
        <x-ui-card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Monthly Overview</h3>
                <x-heroicon-chart-pie class="h-5 w-5 text-gray-400" />
            </div>

            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                <div class="text-center">
                    <x-heroicon-o-chart-bar class="h-16 w-16 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">Chart placeholder</p>
                    <p class="text-sm text-gray-400">Charts will be integrated here</p>
                </div>
            </div>
        </x-ui-card>
    </div>
@endsection
