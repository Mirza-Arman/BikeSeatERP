@extends('layouts.modern')

@section('title', 'Edit Purchase Order')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Purchase Order</h1>
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
            <h3 class="text-lg font-semibold text-gray-900">Edit Purchase Order</h3>
            <x-heroicon-o-pencil class="h-5 w-5 text-gray-400" />
        </div>
        
        <form method="POST" action="{{ route('purchases.purchase-orders.update', $purchaseOrder) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <select name="supplier_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $purchaseOrder->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Date *</label>
                    <input type="date" name="purchase_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required value="{{ $purchaseOrder->purchase_date->format('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery</label>
                    <input type="date" name="expected_delivery" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ $purchaseOrder->expected_delivery?->format('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Number</label>
                    <input type="text" name="invoice_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ $purchaseOrder->invoice_number }}">
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                <div id="items-container" class="space-y-4">
                    @foreach ($purchaseOrder->items as $index => $item)
                        <div class="item-row grid grid-cols-12 gap-4 items-end">
                            <div class="col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Material *</label>
                                <select name="items[{{ $index }}][raw_material_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none raw-material-select" required>
                                    <option value="">Select Material</option>
                                    @foreach ($rawMaterials as $material)
                                        <option value="{{ $material->id }}" data-unit="{{ $material->unit }}" {{ $item->raw_material_id == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                                <input type="number" name="items[{{ $index }}][quantity]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" placeholder="Quantity" required step="0.01" value="{{ $item->quantity }}">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                                <input type="number" name="items[{{ $index }}][unit_price]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" placeholder="Unit Price" required step="0.01" value="{{ $item->unit_price }}">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 unit-display" readonly placeholder="Unit" value="{{ $item->unit }}">
                            </div>
                            <div class="col-span-2">
                                <button type="button" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 remove-item">
                                    <x-heroicon-o-trash class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200" id="add-item">
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Add Item
                </button>
            </div>

            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Details</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discount</label>
                        <input type="number" name="discount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" step="0.01" value="{{ $purchaseOrder->discount }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tax (%)</label>
                        <input type="number" name="tax" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" step="0.01" value="{{ $purchaseOrder->tax }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tax Amount</label>
                        <input type="number" name="tax_amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" step="0.01" value="{{ $purchaseOrder->tax_amount }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transport Cost</label>
                        <input type="number" name="transport_cost" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" step="0.01" value="{{ $purchaseOrder->transport_cost }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Other Cost</label>
                        <input type="number" name="other_cost" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" step="0.01" value="{{ $purchaseOrder->other_cost }}">
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" rows="3">{{ $purchaseOrder->notes }}</textarea>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-check class="h-5 w-5" />
                    Update Purchase Order
                </button>
                <a href="{{ route('purchases.purchase-orders.show', $purchaseOrder) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </x-ui.card>

    <script>
        let itemCount = {{ $purchaseOrder->items->count() }};
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'item-row grid grid-cols-12 gap-4 items-end';
            newRow.innerHTML = `
                <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Material *</label>
                    <select name="items[${itemCount}][raw_material_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none raw-material-select" required>
                        <option value="">Select Material</option>
                        @foreach ($rawMaterials as $material)
                            <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                    <input type="number" name="items[${itemCount}][quantity]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" placeholder="Quantity" required step="0.01">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                    <input type="number" name="items[${itemCount}][unit_price]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" placeholder="Unit Price" required step="0.01">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 unit-display" readonly placeholder="Unit">
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
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('raw-material-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const unitDisplay = e.target.closest('.item-row').querySelector('.unit-display');
                unitDisplay.value = selectedOption.dataset.unit || '';
            }
        });
    </script>
@endsection
