@extends('layouts.modern')

@section('title', 'Receive Goods')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Receive Goods</h1>
                <p class="text-gray-600 mt-2">{{ $purchaseOrder->purchase_number }}</p>
            </div>
            <a href="{{ route('purchases.purchase-orders.show', $purchaseOrder) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
                Back
            </a>
        </div>
    </div>

    <x-ui.card>
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Purchase Order Information</h3>
            <x-heroicon-o-inbox class="h-5 w-5 text-gray-400" />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div>
                <p class="text-sm text-blue-800">Supplier</p>
                <p class="font-medium text-blue-900">{{ $purchaseOrder->supplier->company_name }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-800">Purchase Date</p>
                <p class="font-medium text-blue-900">{{ $purchaseOrder->purchase_date->format('Y-m-d') }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-800">Status</p>
                <p class="font-medium text-blue-900">{{ ucfirst($purchaseOrder->status) }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-800">Expected Delivery</p>
                <p class="font-medium text-blue-900">{{ $purchaseOrder->expected_delivery?->format('Y-m-d') ?? 'N/A' }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('purchases.goods-receipts.store', $purchaseOrder) }}">
            @csrf
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Material</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Ordered</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Already Received</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Receive Quantity</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($purchaseOrder->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->rawMaterial->name }}</td>
                                <td class="px-6 py-4 text-sm text-right text-gray-600">{{ number_format($item->quantity, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-right text-gray-600">{{ number_format($item->received_quantity, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->unit }}</td>
                                <td class="px-6 py-4">
                                    <input type="hidden" name="items[{{ $item->id }}][purchase_order_item_id]" value="{{ $item->id }}">
                                    <input type="number" name="items[{{ $item->id }}][received_quantity]" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" 
                                           step="0.01" 
                                           min="0" 
                                           max="{{ $item->quantity - $item->received_quantity }}"
                                           placeholder="Max: {{ $item->quantity - $item->received_quantity }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                <textarea name="remarks" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" rows="3" placeholder="Any notes about this goods receipt..."></textarea>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-check class="h-5 w-5" />
                    Receive Goods
                </button>
                <a href="{{ route('purchases.purchase-orders.show', $purchaseOrder) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </x-ui.card>
@endsection
