@extends('layouts.modern')

@section('title', 'Raw Material Details')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Raw Material Details</h1>
                <p class="text-gray-600 mt-2">{{ $rawMaterial->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('erp.raw-materials.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-arrow-left class="h-5 w-5" />
                    Back
                </a>
                <a href="{{ route('erp.raw-materials.edit', $rawMaterial) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-pencil class="h-5 w-5" />
                    Edit
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Material Information --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Material Information</h3>
                <x-heroicon-o-cube class="h-5 w-5 text-gray-400" />
            </div>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Code</p>
                        <p class="font-medium text-gray-900">{{ $rawMaterial->material_code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <x-ui.badge :variant="$rawMaterial->status === 'active' ? 'success' : 'secondary'">
                            {{ ucfirst($rawMaterial->status) }}
                        </x-ui.badge>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium text-gray-900">{{ $rawMaterial->name }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="font-medium text-gray-900">{{ $rawMaterial->category?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Supplier</p>
                        <p class="font-medium text-gray-900">{{ $rawMaterial->supplier?->company_name ?? '—' }}</p>
                    </div>
                </div>
                @if (is_array($rawMaterial->attributes) && count($rawMaterial->attributes) > 0)
                    <div>
                        <p class="text-sm text-gray-500">Attributes</p>
                        <div class="text-sm text-gray-900">
                            @foreach ($rawMaterial->attributes as $key => $value)
                                @if ($value)<p>{{ ucfirst($key) }}: {{ $value }}</p>@endif
                            @endforeach
                        </div>
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">Unit</p>
                    <p class="font-medium text-gray-900">{{ $rawMaterial->unit ?? '—' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Current Stock</p>
                        <p class="font-medium text-gray-900">{{ number_format($rawMaterial->current_stock, 2) }} {{ $rawMaterial->unit ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Minimum Stock</p>
                        <p class="font-medium text-gray-900">{{ number_format($rawMaterial->minimum_stock, 2) }} {{ $rawMaterial->unit ?? '' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Purchase Price</p>
                        <p class="font-medium text-gray-900">{{ number_format($rawMaterial->purchase_price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Average Cost</p>
                        <p class="font-medium text-gray-900">{{ number_format($rawMaterial->average_cost, 2) }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Description</p>
                    <p class="font-medium text-gray-900">{{ $rawMaterial->description ?? '—' }}</p>
                </div>
            </div>
        </x-ui.card>

        {{-- Stock Movement --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Stock Movement</h3>
                <x-heroicon-o-arrows-right-left class="h-5 w-5 text-gray-400" />
            </div>
            @if ($detail['stock_movement']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">New Qty</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($detail['stock_movement'] as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $transaction['date']->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $transaction['type'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($transaction['quantity'], 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($transaction['new_quantity'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">No stock transactions found.</p>
                </div>
            @endif
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        {{-- Purchase History --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Purchase History</h3>
                <x-heroicon-o-shopping-cart class="h-5 w-5 text-gray-400" />
            </div>
            @if ($detail['supplier_history']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($detail['supplier_history'] as $purchase)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $purchase['date'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $purchase['supplier'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($purchase['quantity'], 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($purchase['unit_price'], 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($purchase['total'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">No purchase history found.</p>
                </div>
            @endif
        </x-ui.card>

        {{-- Price History --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Price History</h3>
                <x-heroicon-o-chart-bar class="h-5 w-5 text-gray-400" />
            </div>
            @if ($detail['price_history']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($detail['price_history'] as $price)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $price['date'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($price['price'], 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $price['supplier'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">No price history found.</p>
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
