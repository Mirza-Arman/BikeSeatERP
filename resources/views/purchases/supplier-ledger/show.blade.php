@extends('layouts.modern')

@section('title', 'Supplier Ledger - {{ $supplier->company_name }}')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $supplier->company_name }}</h1>
                <p class="text-gray-600 mt-2">Supplier ledger and transaction history</p>
            </div>
            <a href="{{ route('purchases.supplier-ledger.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
                Back
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-ui.card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-shopping-cart class="h-5 w-5 text-blue-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total Purchases</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($summary['total_purchases'], 2) }}</p>
                </div>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-banknotes class="h-5 w-5 text-emerald-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total Payments</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($summary['total_payments'], 2) }}</p>
                </div>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-red-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Outstanding Balance</p>
                    <p class="text-xl font-bold text-red-600">{{ number_format($summary['outstanding_balance'], 2) }}</p>
                </div>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-calendar class="h-5 w-5 text-purple-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Last Purchase</p>
                    <p class="text-xl font-bold text-gray-900">{{ $summary['last_purchase_date'] ? $summary['last_purchase_date']->format('Y-m-d') : 'N/A' }}</p>
                </div>
            </div>
        </x-ui.card>
    </div>

    <x-ui.card>
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Transaction History</h3>
            <x-heroicon-o-list class="h-5 w-5 text-gray-400" />
        </div>
        
        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Debit</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Credit</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Balance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($ledger['transactions'] as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $transaction['date']->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-ui.badge :variant="$transaction['type'] === 'debit' ? 'danger' : 'success'">
                                    {{ ucfirst($transaction['type']) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $transaction['reference'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction['description'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">{{ $transaction['debit'] > 0 ? number_format($transaction['debit'], 2) : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">{{ $transaction['credit'] > 0 ? number_format($transaction['credit'], 2) : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($transaction['balance'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                                    <p class="text-gray-500">No transactions found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
