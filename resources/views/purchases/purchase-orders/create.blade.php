@extends('layouts.modern')

@section('title', 'Create Purchase Order')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('purchases.purchase-orders.index') }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <x-heroicon-arrow-left class="h-6 w-6" />
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Purchase Order</h1>
                <p class="text-gray-600 mt-2">Add a new purchase order to your system</p>
            </div>
        </div>
    </div>

    <div class="max-w-5xl">
        <x-ui-card>
            <form method="POST" action="{{ route('purchases.purchase-orders.store') }}">
                @csrf
                
                {{-- Basic Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-document-text class="h-5 w-5 text-blue-600" />
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Supplier <span class="text-red-500">*</span></label>
                            <select name="supplier_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Date <span class="text-red-500">*</span></label>
                            <input type="date" name="purchase_date" required value="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery</label>
                            <input type="date" name="expected_delivery" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Number</label>
                            <input type="text" name="invoice_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="INV-001">
                        </div>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-shopping-cart class="h-5 w-5 text-blue-600" />
                        Order Items
                    </h3>
                    <div id="items-container" class="space-y-4">
                        <div class="item-row grid grid-cols-12 gap-4 items-end bg-gray-50 p-4 rounded-lg">
                            <div class="col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Material <span class="text-red-500">*</span></label>
                                <select name="items[0][raw_material_id]" class="raw-material-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" required>
                                    <option value="">Select Material</option>
                                    @foreach ($rawMaterials as $material)
                                        <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">{{ $material->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                                <input type="number" name="items[0][quantity]" placeholder="0.00" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price <span class="text-red-500">*</span></label>
                                <input type="number" name="items[0][unit_price]" placeholder="0.00" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <input type="text" class="unit-display w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100" readonly>
                            </div>
                            <div class="col-span-2">
                                <button type="button" class="w-full px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors remove-item">
                                    <x-heroicon-trash class="h-5 w-5 mx-auto" />
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-item" class="mt-4 inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <x-heroicon-plus class="h-5 w-5" />
                        Add Item
                    </button>
                </div>

                {{-- Payment & Costs --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-currency-dollar class="h-5 w-5 text-blue-600" />
                        Payment & Costs
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount</label>
                            <input type="number" name="discount" step="0.01" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tax (%)</label>
                            <input type="number" name="tax" step="0.01" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tax Amount</label>
                            <input type="number" name="tax_amount" step="0.01" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Transport Cost</label>
                            <input type="number" name="transport_cost" step="0.01" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Other Cost</label>
                            <input type="number" name="other_cost" step="0.01" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Initial Payment</label>
                            <input type="number" name="paid_amount" step="0.01" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                            <input type="text" name="reference_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="REF-001">
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-document class="h-5 w-5 text-blue-600" />
                        Additional Notes
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none resize-none" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('purchases.purchase-orders.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <x-heroicon-x-mark class="h-5 w-5" />
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <x-heroicon-check class="h-5 w-5" />
                        Create Purchase Order
                    </button>
                </div>
            </form>
        </x-ui-card>
    </div>

    <script>
        let itemCount = 1;
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'item-row grid grid-cols-12 gap-4 items-end bg-gray-50 p-4 rounded-lg';
            newRow.innerHTML = `
                <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Material <span class="text-red-500">*</span></label>
                    <select name="items[${itemCount}][raw_material_id]" class="raw-material-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" required>
                        <option value="">Select Material</option>
                        @foreach ($rawMaterials as $material)
                            <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="items[${itemCount}][quantity]" placeholder="0.00" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price <span class="text-red-500">*</span></label>
                    <input type="number" name="items[${itemCount}][unit_price]" placeholder="0.00" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                    <input type="text" class="unit-display w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100" readonly>
                </div>
                <div class="col-span-2">
                    <button type="button" class="w-full px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors remove-item">
                        <x-heroicon-trash class="h-5 w-5 mx-auto" />
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            itemCount++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
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
