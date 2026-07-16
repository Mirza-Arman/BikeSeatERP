@extends('layouts.app')

@section('title', 'Edit Purchase Order')
@section('page-title', 'Edit Purchase Order')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Purchase Order: {{ $purchaseOrder->purchase_number }}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('purchases.purchase-orders.update', $purchaseOrder) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Supplier *</label>
                            <select name="supplier_id" class="form-control" required>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $purchaseOrder->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Purchase Date *</label>
                            <input type="date" name="purchase_date" class="form-control" required value="{{ $purchaseOrder->purchase_date->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Expected Delivery</label>
                            <input type="date" name="expected_delivery" class="form-control" value="{{ $purchaseOrder->expected_delivery?->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control" value="{{ $purchaseOrder->invoice_number }}">
                        </div>
                    </div>
                </div>

                <hr>
                <h4>Items</h4>
                <div id="items-container">
                    @foreach ($purchaseOrder->items as $index => $item)
                        <div class="item-row row mb-2">
                            <div class="col-md-4">
                                <select name="items[{{ $index }}][raw_material_id]" class="form-control raw-material-select" required>
                                    <option value="">Select Material</option>
                                    @foreach ($rawMaterials as $material)
                                        <option value="{{ $material->id }}" data-unit="{{ $material->unit }}" {{ $item->raw_material_id == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="items[{{ $index }}][quantity]" class="form-control" placeholder="Quantity" required step="0.01" value="{{ $item->quantity }}">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="items[{{ $index }}][unit_price]" class="form-control" placeholder="Unit Price" required step="0.01" value="{{ $item->unit_price }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control unit-display" readonly placeholder="Unit" value="{{ $item->unit }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-item">Remove</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary btn-sm" id="add-item">Add Item</button>

                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Discount</label>
                            <input type="number" name="discount" class="form-control" step="0.01" value="{{ $purchaseOrder->discount }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tax (%)</label>
                            <input type="number" name="tax" class="form-control" step="0.01" value="{{ $purchaseOrder->tax }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tax Amount</label>
                            <input type="number" name="tax_amount" class="form-control" step="0.01" value="{{ $purchaseOrder->tax_amount }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Transport Cost</label>
                            <input type="number" name="transport_cost" class="form-control" step="0.01" value="{{ $purchaseOrder->transport_cost }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Other Cost</label>
                            <input type="number" name="other_cost" class="form-control" step="0.01" value="{{ $purchaseOrder->other_cost }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ $purchaseOrder->notes }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Purchase Order</button>
                <a href="{{ route('purchases.purchase-orders.show', $purchaseOrder) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script>
        let itemCount = {{ $purchaseOrder->items->count() }};
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'item-row row mb-2';
            newRow.innerHTML = `
                <div class="col-md-4">
                    <select name="items[${itemCount}][raw_material_id]" class="form-control raw-material-select" required>
                        <option value="">Select Material</option>
                        @foreach ($rawMaterials as $material)
                            <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[${itemCount}][quantity]" class="form-control" placeholder="Quantity" required step="0.01">
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[${itemCount}][unit_price]" class="form-control" placeholder="Unit Price" required step="0.01">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control unit-display" readonly placeholder="Unit">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            `;
            container.appendChild(newRow);
            itemCount++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
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
