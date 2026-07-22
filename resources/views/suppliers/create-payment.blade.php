@extends('layouts.app')

@section('title', 'Record Payment')
@section('page-title', 'Record Payment')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('erp.suppliers.show', $supplier) }}" class="btn btn-secondary">Back to Supplier</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Record Payment - {{ $supplier->company_name }}</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Current Balance:</strong> {{ number_format($supplier->balance, 2) }}
            </div>
            <form method="POST" action="{{ route('erp.suppliers.store-payment') }}">
                @csrf
                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Amount *</label>
                            <input type="number" name="amount" class="form-control" required step="0.01" min="0.01">
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Record Payment</button>
            </form>
        </div>
    </div>
@endsection
