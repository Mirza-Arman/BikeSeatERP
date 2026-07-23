@extends('layouts.modern')

@section('title', 'Supplier Details')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Supplier Details</h1>
                <p class="text-gray-600 mt-2">{{ $supplier->company_name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('erp.suppliers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-arrow-left class="h-5 w-5" />
                    Back
                </a>
                <a href="{{ route('erp.suppliers.ledger', $supplier) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-book class="h-5 w-5" />
                    Ledger
                </a>
                <a href="{{ route('erp.suppliers.materials-supplied', $supplier) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-cube class="h-5 w-5" />
                    Materials
                </a>
                <a href="{{ route('erp.suppliers.create-payment', $supplier) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-currency-dollar class="h-5 w-5" />
                    Record Payment
                </a>
                <a href="{{ route('erp.suppliers.edit', $supplier) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-pencil class="h-5 w-5" />
                    Edit
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <x-ui.card padding="sm">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Total Purchases</p>
                <p class="text-2xl font-bold text-primary-600">{{ $dashboard['total_purchases'] }}</p>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                <p class="text-2xl font-bold text-emerald-600">{{ number_format($dashboard['total_amount_purchased'], 2) }}</p>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Total Paid</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($dashboard['total_paid'], 2) }}</p>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Balance</p>
                <p class="text-2xl font-bold {{ $dashboard['remaining_balance'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($dashboard['remaining_balance'], 2) }}</p>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Materials</p>
                <p class="text-2xl font-bold text-purple-600">{{ $dashboard['materials_supplied'] }}</p>
            </div>
        </x-ui.card>
        <x-ui.card padding="sm">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-1">Last Purchase</p>
                <p class="text-lg font-semibold text-gray-900">{{ $dashboard['last_purchase_date'] ?? '—' }}</p>
            </div>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Supplier Information --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Supplier Information</h3>
                <x-heroicon-o-identification class="h-5 w-5 text-gray-400" />
            </div>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Code</p>
                        <p class="font-medium text-gray-900">{{ $supplier->supplier_code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <x-ui.badge :variant="$supplier->status === 'active' ? 'success' : 'secondary'">
                            {{ ucfirst($supplier->status) }}
                        </x-ui.badge>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Company</p>
                    <p class="font-medium text-gray-900">{{ $supplier->company_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Contact Person</p>
                    <p class="font-medium text-gray-900">{{ $supplier->contact_person ?? '—' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium text-gray-900">{{ $supplier->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">WhatsApp</p>
                        <p class="font-medium text-gray-900">{{ $supplier->whatsapp ?? '—' }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-900">{{ $supplier->email ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Address</p>
                    <p class="font-medium text-gray-900">{{ $supplier->address ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">City</p>
                    <p class="font-medium text-gray-900">{{ $supplier->city ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Notes</p>
                    <p class="font-medium text-gray-900">{{ $supplier->notes ?? '—' }}</p>
                </div>
            </div>
        </x-ui.card>

        {{-- Recent Purchases --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Purchases</h3>
                <x-heroicon-o-shopping-cart class="h-5 w-5 text-gray-400" />
            </div>
            @if ($dashboard['recent_purchases']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">PO Number</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($dashboard['recent_purchases'] as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->purchase_number }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $order->purchase_date }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($order->grand_total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">No recent purchases found.</p>
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
