@extends('layouts.modern')

@section('title', 'Purchase Orders')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Purchase Orders</h1>
                <p class="text-gray-600 mt-2">Manage purchase orders and supplier payments</p>
            </div>
            <a href="{{ route('purchases.purchase-orders.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-plus class="h-5 w-5" />
                New Purchase Order
            </a>
        </div>
    </div>

    <x-ui.card>
        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Paid</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Remaining</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($purchaseOrders as $purchaseOrder)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $purchaseOrder->invoice_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $purchaseOrder->supplier?->company_name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $purchaseOrder->purchase_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($purchaseOrder->grand_total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($purchaseOrder->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($purchaseOrder->remaining_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-ui.badge :variant="$purchaseOrder->status === 'received' ? 'success' : 'warning'">
                                    {{ ucfirst($purchaseOrder->status) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('purchases.purchase-orders.show', $purchaseOrder) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                        View
                                    </a>
                                    <a href="{{ route('purchases.purchase-orders.edit', $purchaseOrder) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                        Edit
                                    </a>
                                    <form action="{{ route('purchases.purchase-orders.destroy', $purchaseOrder) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200" type="submit" onclick="return confirm('Delete this purchase order?')">
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
                                    <x-heroicon-o-document-text class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                                    <p class="text-gray-500">No purchase orders found</p>
                                    <a href="{{ route('purchases.purchase-orders.create') }}" class="mt-2 text-blue-600 hover:text-blue-700">Create your first purchase order</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($purchaseOrders->hasPages())
            <div class="mt-6">
                {{ $purchaseOrders->links() }}
            </div>
        @endif
    </x-ui.card>
@endsection
