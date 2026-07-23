@extends('layouts.modern')

@section('title', 'Product Details')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Product Details</h1>
                <p class="text-gray-600 mt-2">{{ $product->product_name }}</p>
            </div>
            <a href="{{ route('erp.inventory.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Product Information --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Product Information</h3>
                <x-heroicon-o-cube class="h-5 w-5 text-gray-400" />
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Code</p>
                    <p class="font-medium text-gray-900">{{ $product->product_code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium text-gray-900">{{ $product->product_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Model</p>
                    <p class="font-medium text-gray-900">{{ $product->model ?? '—' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Selling Price</p>
                        <p class="font-medium text-gray-900">{{ number_format($product->selling_price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <x-ui.badge :variant="$product->status === 'active' ? 'success' : 'secondary'">
                            {{ ucfirst($product->status) }}
                        </x-ui.badge>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Current Stock</p>
                        <p class="font-medium text-gray-900">{{ number_format($product->current_stock, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Minimum Stock</p>
                        <p class="font-medium text-gray-900">{{ number_format($product->minimum_stock, 2) }}</p>
                    </div>
                </div>
            </div>
        </x-ui.card>

        {{-- Recent Transactions --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                <x-heroicon-o-arrows-right-left class="h-5 w-5 text-gray-400" />
            </div>
            @if ($product->finishedGoodsTransactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Balance</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($product->finishedGoodsTransactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($transaction->transaction_type) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium {{ $transaction->transaction_type === 'in' ? 'text-emerald-600' : 'text-red-600' }}">{{ number_format($transaction->quantity, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($transaction->balance_after, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $transaction->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">No transactions found.</p>
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
