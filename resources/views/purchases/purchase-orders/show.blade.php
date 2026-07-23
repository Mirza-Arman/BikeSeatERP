@extends('layouts.modern')

@section('title', 'Purchase Order Details')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Purchase Order Details</h1>
                <p class="text-gray-600 mt-2">{{ $purchaseOrder->purchase_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if ($purchaseOrder->status !== 'completed')
                    <a href="{{ route('purchases.goods-receipts.create', $purchaseOrder) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-all duration-200">
                        <x-heroicon-o-check class="h-5 w-5" />
                        Receive Goods
                    </a>
                    <a href="{{ route('purchases.payments.create', $purchaseOrder) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                        <x-heroicon-o-currency-dollar class="h-5 w-5" />
                        Add Payment
                    </a>
                    <a href="{{ route('purchases.purchase-orders.edit', $purchaseOrder) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-all duration-200">
                        <x-heroicon-o-pencil class="h-5 w-5" />
                        Edit
                    </a>
                @endif
                <a href="{{ route('purchases.purchase-orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-arrow-left class="h-5 w-5" />
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Order Details --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Order Details</h3>
                <x-heroicon-o-document-text class="h-5 w-5 text-gray-400" />
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Supplier</p>
                    <p class="font-medium text-gray-900">{{ $purchaseOrder->supplier->company_name }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Purchase Date</p>
                        <p class="font-medium text-gray-900">{{ $purchaseOrder->purchase_date->format('Y-m-d') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Expected Delivery</p>
                        <p class="font-medium text-gray-900">{{ $purchaseOrder->expected_delivery ? $purchaseOrder->expected_delivery->format('Y-m-d') : 'N/A' }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Invoice Number</p>
                    <p class="font-medium text-gray-900">{{ $purchaseOrder->invoice_number ?? 'N/A' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <x-ui.badge :variant="$purchaseOrder->status === 'completed' ? 'success' : ($purchaseOrder->status === 'partial' ? 'warning' : 'secondary')">
                            {{ ucfirst($purchaseOrder->status) }}
                        </x-ui.badge>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Payment Status</p>
                        <x-ui.badge :variant="$purchaseOrder->payment_status === 'paid' ? 'success' : ($purchaseOrder->payment_status === 'partial' ? 'warning' : 'danger')">
                            {{ ucfirst($purchaseOrder->payment_status) }}
                        </x-ui.badge>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created By</p>
                    <p class="font-medium text-gray-900">{{ $purchaseOrder->createdBy?->name ?? 'N/A' }}</p>
                </div>
                @if ($purchaseOrder->notes)
                    <div>
                        <p class="text-sm text-gray-500">Notes</p>
                        <p class="font-medium text-gray-900">{{ $purchaseOrder->notes }}</p>
                    </div>
                @endif
            </div>
        </x-ui.card>

        {{-- Financial Summary --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Financial Summary</h3>
                <x-heroicon-o-currency-dollar class="h-5 w-5 text-gray-400" />
            </div>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Subtotal</span>
                    <span class="font-medium text-gray-900">{{ number_format($purchaseOrder->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Discount</span>
                    <span class="font-medium text-red-600">-{{ number_format($purchaseOrder->discount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Tax Amount</span>
                    <span class="font-medium text-gray-900">{{ number_format($purchaseOrder->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Transport Cost</span>
                    <span class="font-medium text-gray-900">{{ number_format($purchaseOrder->transport_cost, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Other Cost</span>
                    <span class="font-medium text-gray-900">{{ number_format($purchaseOrder->other_cost, 2) }}</span>
                </div>
                <div class="border-t pt-3 flex justify-between">
                    <span class="text-sm font-semibold text-gray-900">Grand Total</span>
                    <span class="font-bold text-lg text-gray-900">{{ number_format($purchaseOrder->grand_total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Paid Amount</span>
                    <span class="font-medium text-emerald-600">{{ number_format($purchaseOrder->paid_amount, 2) }}</span>
                </div>
                <div class="border-t pt-3 flex justify-between">
                    <span class="text-sm font-semibold text-gray-900">Remaining Amount</span>
                    <span class="font-bold text-lg {{ $purchaseOrder->remaining_amount > 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ number_format($purchaseOrder->remaining_amount, 2) }}</span>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Items --}}
    <x-ui.card class="mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
            <x-heroicon-o-cube class="h-5 w-5 text-gray-400" />
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Received</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Pending</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($purchaseOrder->items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->rawMaterial->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($item->quantity, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($item->total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-emerald-600">{{ number_format($item->received_quantity, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-orange-600">{{ number_format($item->quantity - $item->received_quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Goods Receipts --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Goods Receipts</h3>
                <x-heroicon-o-inbox class="h-5 w-5 text-gray-400" />
            </div>
            @if ($purchaseOrder->goodsReceipts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Receipt Number</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Received Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Remarks</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($purchaseOrder->goodsReceipts as $receipt)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $receipt->receipt_number }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $receipt->received_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $receipt->remarks ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $receipt->createdBy?->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">No goods receipts yet.</p>
                </div>
            @endif
        </x-ui.card>

        {{-- Payments --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Payments</h3>
                <x-heroicon-o-currency-dollar class="h-5 w-5 text-gray-400" />
            </div>
            @if ($purchaseOrder->payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reference</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($purchaseOrder->payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $payment->payment_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $payment->payment_method }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->reference_number ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <form action="{{ route('purchases.payments.destroy', $payment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200" onclick="return confirm('Delete this payment?')">
                                                <x-heroicon-o-trash class="h-4 w-4" />
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">No payments yet.</p>
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
