@extends('layouts.modern')

@section('title', 'Delivery Records')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Delivery Records</h1>
                <p class="text-gray-600 mt-2">Track customer order deliveries</p>
            </div>
            <a href="{{ route('erp.customers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
                Back to Customers
            </a>
        </div>
    </div>

    <x-ui.card>
        {{-- Filters --}}
        <form method="GET" class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1 relative">
                <x-heroicon-o-magnifying-glass class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by customer or order..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                    Search
                </button>
                @if (request('search'))
                    <a href="{{ route('erp.customers.delivery-records.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <x-heroicon-o-arrow-path class="h-5 w-5" />
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($deliveries ?? [] as $delivery)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $delivery->delivery_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $delivery->customer?->customer_name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $delivery->customerOrder?->order_number ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($delivery->quantity_delivered, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-ui.badge :variant="$delivery->status === 'delivered' ? 'success' : 'warning'">
                                    {{ ucfirst($delivery->status) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $delivery->notes ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('erp.customers.orders.show', $delivery->customer_order_id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                    <x-heroicon-o-eye class="h-4 w-4" />
                                    View Order
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-o-truck class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                                    <p class="text-gray-500">No delivery records found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (isset($deliveries) && $deliveries->hasPages())
            <div class="mt-6">
                {{ $deliveries->links() }}
            </div>
        @endif
    </x-ui.card>
@endsection
