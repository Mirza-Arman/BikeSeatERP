@extends('layouts.modern')

@section('title', 'Purchase Payments')
@section('page-title', 'Purchase Payments')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Purchase Payments</h3>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <select name="supplier_id" class="form-control mr-2">
                    <option value="">All Suppliers</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                    @endforeach
                </select>
                <select name="payment_method" class="form-control mr-2">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="cheque" {{ request('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                </select>
                <input type="date" name="date_from" class="form-control mr-2" value="{{ request('date_from') }}" placeholder="From">
                <input type="date" name="date_to" class="form-control mr-2" value="{{ request('date_to') }}" placeholder="To">
                <button type="submit" class="btn btn-outline-secondary">Filter</button>
                <a href="{{ route('purchases.payments.index') }}" class="btn btn-link">Clear</a>
            </form>

            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Today's Payments</h5>
                            <h3>{{ number_format($statistics['total_payments_today'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Monthly Payments</h5>
                            <h3>{{ number_format($statistics['total_payments_month'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Pending Payments</h5>
                            <h3>{{ $statistics['pending_payments'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">Overdue Payments</h5>
                            <h3>{{ $statistics['overdue_payments'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                <td>{{ $payment->purchaseOrder->purchase_number }}</td>
                                <td>{{ $payment->supplier->company_name }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                <td>{{ $payment->createdBy?->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('purchases.purchase-orders.show', $payment->purchase_order_id) }}" class="btn btn-sm btn-info">View PO</a>
                                    <form action="{{ route('purchases.payments.destroy', $payment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No payments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $payments->links() }}
        </div>
    </div>
@endsection
