@extends('layouts.app')

@section('title', 'Add Payment')
@section('page-title', 'Add Payment - {{ $purchaseOrder->purchase_number }}')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add Payment for {{ $purchaseOrder->purchase_number }}</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->company_name }}</p>
                    <p><strong>Grand Total:</strong> {{ number_format($purchaseOrder->grand_total, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Paid Amount:</strong> {{ number_format($purchaseOrder->paid_amount, 2) }}</p>
                    <p><strong>Remaining Amount:</strong> {{ number_format($purchaseOrder->remaining_amount, 2) }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('purchases.payments.store') }}">
                @csrf
                <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
                <input type="hidden" name="supplier_id" value="{{ $purchaseOrder->supplier_id }}">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Amount *</label>
                            <input type="number" name="amount" class="form-control" required step="0.01" max="{{ $purchaseOrder->remaining_amount }}" placeholder="Max: {{ $purchaseOrder->remaining_amount }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Payment Method *</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Payment Date *</label>
                            <input type="date" name="payment_date" class="form-control" required value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Reference Number</label>
                            <input type="text" name="reference_number" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Bank</label>
                            <input type="text" name="bank" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cheque Number</label>
                            <input type="text" name="cheque_number" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3" placeholder="Any notes about this payment..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Record Payment</button>
                <a href="{{ route('purchases.purchase-orders.show', $purchaseOrder) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
