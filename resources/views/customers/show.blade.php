@extends('layouts.modern')

@section('title', 'Customer Details')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Customer Details</h1>
                <p class="text-gray-600 mt-2">{{ $customer->customer_name }} - {{ $customer->customer_code }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('erp.customers.edit', $customer) }}?redirect=show" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-pencil class="h-5 w-5" />
                    Edit
                </a>
                <a href="{{ route('erp.customers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-arrow-left class="h-5 w-5" />
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Customer Information --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                <x-heroicon-o-user class="h-5 w-5 text-gray-400" />
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Code</span>
                    <span class="text-sm font-medium text-gray-900">{{ $customer->customer_code }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Name</span>
                    <span class="text-sm font-medium text-gray-900">{{ $customer->customer_name }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Phone</span>
                    <span class="text-sm font-medium text-gray-900">{{ $customer->phone ?? '—' }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Email</span>
                    <span class="text-sm font-medium text-gray-900">{{ $customer->email ?? '—' }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Address</span>
                    <span class="text-sm font-medium text-gray-900">{{ $customer->address ?? '—' }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">City</span>
                    <span class="text-sm font-medium text-gray-900">{{ $customer->city ?? '—' }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-600">Outstanding Balance</span>
                    <span class="text-sm font-medium {{ $customer->balance > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($customer->balance, 2) }}</span>
                </div>
            </div>
        </x-ui.card>

        {{-- Recent Orders --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <x-heroicon-o-shopping-cart class="h-5 w-5 text-gray-400" />
            </div>
            @if ($customer->orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($customer->orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $order->invoice_no }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $order->order_date }}</td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-600">{{ number_format($order->grand_total, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <x-ui.badge :variant="$order->status === 'completed' ? 'success' : 'warning'">
                                            {{ ucfirst($order->status) }}
                                        </x-ui.badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-shopping-cart class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">No orders found</p>
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
