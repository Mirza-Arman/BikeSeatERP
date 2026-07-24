@extends('layouts.modern')

@section('title', 'Create Purchase Order')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Purchase Order</h1>
                <p class="text-gray-600 mt-2">Create a new purchase order for materials</p>
            </div>
            <a href="{{ route('purchases.purchase-orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
                Back
            </a>
        </div>
    </div>

    <x-ui.card>
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Purchase Order Details</h3>
            <x-heroicon-o-document-plus class="h-5 w-5 text-gray-400" />
        </div>
        
        <form method="POST" action="{{ route('purchases.purchase-orders.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <select name="supplier_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                        <option value="">Select supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Number</label>
                    <input type="text" name="invoice_no" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" placeholder="Auto-generated if empty">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Date *</label>
                    <input type="date" name="purchase_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                        <option value="pending">Pending</option>
                        <option value="received">Received</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                <div id="items-container" class="space-y-4">
                    <div class="item-row grid grid-cols-12 gap-4 items-end">
                        <div class="col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Raw Material *</label>
                            <select name="items[0][raw_material_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                                <option value="">Select material</option>
                                @foreach ($rawMaterials as $material)
                                    <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                            <input type="number" step="0.01" name="items[0][quantity]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                            <input type="number" step="0.01" name="items[0][unit_price]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 item-total" readonly>
                        </div>
                        <div class="col-span-2">
                            <button type="button" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 remove-item">
                                <x-heroicon-o-trash class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200" id="add-item">
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Add Item
                </button>
            </div>

            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tax</label>
                        <input type="number" step="0.01" name="tax" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Paid Amount</label>
                        <input type="number" step="0.01" name="paid_amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grand Total</label>
                        <input type="text" id="grand-total" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none" readonly>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-check class="h-5 w-5" />
                    Save Purchase Order
                </button>
                <a href="{{ route('purchases.purchase-orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </x-ui.card>

    <script>
        let itemCount = 1;

        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'item-row grid grid-cols-12 gap-4 items-end';
            newRow.innerHTML = `
                <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Raw Material *</label>
                    <select name="items[${itemCount}][raw_material_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                        <option value="">Select material</option>
                        @foreach ($rawMaterials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                    <input type="number" step="0.01" name="items[${itemCount}][quantity]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                    <input type="number" step="0.01" name="items[${itemCount}][unit_price]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 item-total" readonly>
                </div>
                <div class="col-span-2">
                    <button type="button" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 remove-item">
                        <x-heroicon-o-trash class="h-5 w-5" />
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            itemCount++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
                e.target.closest('.item-row').remove();
                calculateTotals();
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.name && (e.target.name.includes('quantity') || e.target.name.includes('unit_price'))) {
                calculateTotals();
            }
        });

        function calculateTotals() {
            let subtotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('input[name*="quantity"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name*="unit_price"]').value) || 0;
                const total = qty * price;
                row.querySelector('.item-total').value = total.toFixed(2);
                subtotal += total;
            });

            const tax = parseFloat(document.querySelector('input[name="tax"]').value) || 0;
            const grandTotal = subtotal + tax;
            document.getElementById('grand-total').value = grandTotal.toFixed(2);
        }
    </script>
@endsection
