@extends('layouts.app')

@section('title', 'Create Purchase Order')
@section('page-title', 'Create Purchase Order')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">New Purchase Order</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('purchases.purchase-orders.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Supplier *</label>
                            <select name="supplier_id" class="form-control" required>
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Purchase Date *</label>
                            <input type="date" name="purchase_date" class="form-control" required value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Expected Delivery</label>
                            <input type="date" name="expected_delivery" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control">
                        </div>
                    </div>
                </div>

                <hr>
                <h4>Items</h4>
                <div id="items-container">
                    <div class="item-row row mb-2">
                        <div class="col-md-4">
                            <select name="items[0][raw_material_id]" class="form-control raw-material-select" required>
                                <option value="">Select Material</option>
                                @foreach ($rawMaterials as $material)
                                    <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[0][quantity]" class="form-control" placeholder="Quantity" required step="0.01">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[0][unit_price]" class="form-control" placeholder="Unit Price" required step="0.01">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control unit-display" readonly placeholder="Unit">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-item">Remove</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" id="add-item">Add Item</button>

                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Discount</label>
                            <input type="number" name="discount" class="form-control" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tax (%)</label>
                            <input type="number" name="tax" class="form-control" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tax Amount</label>
                            <input type="number" name="tax_amount" class="form-control" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Transport Cost</label>
                            <input type="number" name="transport_cost" class="form-control" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Other Cost</label>
                            <input type="number" name="other_cost" class="form-control" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Initial Payment</label>
                            <input type="number" name="paid_amount" class="form-control" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Reference Number</label>
                            <input type="text" name="reference_number" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Create Purchase Order</button>
                <a href="{{ route('purchases.purchase-orders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script>
        let itemCount = 1;
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
