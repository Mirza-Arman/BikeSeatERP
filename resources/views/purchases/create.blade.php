@extends('layouts.modern')

@section('title', 'Create Purchase Order')
@section('page-title', 'Create Purchase Order')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('purchases.purchase-orders.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Supplier</label>
                        <select name="supplier_id" class="form-control" required>
                            <option value="">Select supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Invoice number</label>
                        <input type="text" name="invoice_no" class="form-control" placeholder="Auto-generated if empty">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Purchase date</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="pending">Pending</option>
                            <option value="received">Received</option>
                        </select>
                    </div>
                </div>

                <hr>
                <h5>Order Items</h5>
                <div id="items-container">
                    <div class="row item-row mb-2">
                        <div class="col-md-4">
                            <label>Raw Material</label>
                            <select name="items[0][raw_material_id]" class="form-control" required>
                                <option value="">Select material</option>
                                @foreach ($rawMaterials as $material)
                                    <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Quantity</label>
                            <input type="number" step="0.01" name="items[0][quantity]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Unit Price</label>
                            <input type="number" step="0.01" name="items[0][unit_price]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Total</label>
                            <input type="text" class="form-control item-total" readonly>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary btn-sm mb-3" id="add-item">Add Item</button>

                <hr>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Tax</label>
                        <input type="number" step="0.01" name="tax" class="form-control" value="0">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Paid Amount</label>
                        <input type="number" step="0.01" name="paid_amount" class="form-control" value="0">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Grand Total</label>
                        <input type="text" id="grand-total" class="form-control" readonly>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('purchases.purchase-orders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script>
        let itemCount = 1;

        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'row item-row mb-2';
            newRow.innerHTML = `
                <div class="col-md-4">
                    <label>Raw Material</label>
                    <select name="items[${itemCount}][raw_material_id]" class="form-control" required>
                        <option value="">Select material</option>
                        @foreach ($rawMaterials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Quantity</label>
                    <input type="number" step="0.01" name="items[${itemCount}][quantity]" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label>Unit Price</label>
                    <input type="number" step="0.01" name="items[${itemCount}][unit_price]" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label>Total</label>
                    <input type="text" class="form-control item-total" readonly>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                </div>
            `;
            container.appendChild(newRow);
            itemCount++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
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
