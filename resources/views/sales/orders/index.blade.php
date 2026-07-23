@extends('layouts.modern')

@section('title', 'Customer Orders')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Customer Orders</h1>
                <p class="text-gray-600 mt-2">Manage customer orders</p>
            </div>
            <a href="{{ route('erp.customers.orders.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-plus class="h-5 w-5" />
                New Order
            </a>
        </div>
    </div>

    <x-ui.card>
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Orders</h3>
            <x-heroicon-o-shopping-cart class="h-5 w-5 text-gray-400" />
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($customerOrders as $customerOrder)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $customerOrder->invoice_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $customerOrder->customer?->customer_name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $customerOrder->order_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-ui.badge :variant="$customerOrder->status === 'completed' ? 'success' : 'secondary'">
                                    {{ ucfirst($customerOrder->status) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('erp.customers.orders.show', $customerOrder) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                        View
                                    </a>
                                    @if ($customerOrder->status !== 'completed')
                                        <form action="{{ route('erp.customers.orders.status', $customerOrder) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="completed">
                                            <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200" type="submit">
                                                <x-heroicon-o-check class="h-4 w-4" />
                                                Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                                <p class="text-gray-500">No customer orders found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($customerOrders->hasPages())
            <div class="mt-6">
                {{ $customerOrders->links() }}
            </div>
        @endif
    </x-ui.card>
@endsection
