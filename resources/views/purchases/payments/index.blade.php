@extends('layouts.modern')

@section('title', 'Purchase Payments')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Purchase Payments</h1>
                <p class="text-gray-600 mt-2">Manage supplier payments and transactions</p>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-ui.card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-calendar class="h-5 w-5 text-blue-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Today's Payments</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($statistics['total_payments_today'], 2) }}</p>
                </div>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-chart-bar class="h-5 w-5 text-emerald-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Monthly Payments</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($statistics['total_payments_month'], 2) }}</p>
                </div>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-clock class="h-5 w-5 text-orange-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Pending Payments</p>
                    <p class="text-xl font-bold text-orange-600">{{ $statistics['pending_payments'] }}</p>
                </div>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-red-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Overdue Payments</p>
                    <p class="text-xl font-bold text-red-600">{{ $statistics['overdue_payments'] }}</p>
                </div>
            </div>
        </x-ui.card>
    </div>

    <x-ui.card>
        {{-- Filters --}}
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <select name="supplier_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                    <option value="">All Suppliers</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="cheque" {{ request('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                </select>
            </div>
            <div>
                <input type="date" name="date_from" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ request('date_from') }}" placeholder="From">
            </div>
            <div>
                <input type="date" name="date_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ request('date_to') }}" placeholder="To">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <x-heroicon-o-funnel class="h-5 w-5" />
                </button>
                <a href="{{ route('purchases.payments.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">
                    Clear
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">PO Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $payment->payment_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payment->purchaseOrder->purchase_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $payment->supplier->company_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($payment->payment_method) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $payment->reference_number ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $payment->createdBy?->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('purchases.purchase-orders.show', $payment->purchase_order_id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                        View PO
                                    </a>
                                    <form action="{{ route('purchases.payments.destroy', $payment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200" onclick="return confirm('Delete this payment?')">
                                            <x-heroicon-o-trash class="h-4 w-4" />
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-o-currency-dollar class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                                    <p class="text-gray-500">No payments found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($payments->hasPages())
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        @endif
    </x-ui.card>
@endsection
