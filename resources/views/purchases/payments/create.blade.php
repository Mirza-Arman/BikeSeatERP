@extends('layouts.modern')

@section('title', 'Add Payment')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add Payment</h1>
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
            <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
            <x-heroicon-o-currency-dollar class="h-5 w-5 text-gray-400" />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div>
                <p class="text-sm text-blue-800">Supplier</p>
                <p class="font-medium text-blue-900">{{ $purchaseOrder->supplier->company_name }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-800">Grand Total</p>
                <p class="font-medium text-blue-900">{{ number_format($purchaseOrder->grand_total, 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-800">Paid Amount</p>
                <p class="font-medium text-blue-900">{{ number_format($purchaseOrder->paid_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-800">Remaining Amount</p>
                <p class="font-medium text-blue-900">{{ number_format($purchaseOrder->remaining_amount, 2) }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('purchases.payments.store') }}">
            @csrf
            <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
            <input type="hidden" name="supplier_id" value="{{ $purchaseOrder->supplier_id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                    <input type="number" name="amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required step="0.01" max="{{ $purchaseOrder->remaining_amount }}" placeholder="Max: {{ $purchaseOrder->remaining_amount }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                    <input type="date" name="payment_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required value="{{ now()->format('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                    <input type="text" name="reference_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank</label>
                    <input type="text" name="bank" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cheque Number</label>
                    <input type="text" name="cheque_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                <textarea name="remarks" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" rows="3" placeholder="Any notes about this payment..."></textarea>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-check class="h-5 w-5" />
                    Record Payment
                </button>
                <a href="{{ route('purchases.purchase-orders.show', $purchaseOrder) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </x-ui.card>
@endsection
