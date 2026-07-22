@extends('layouts.app')

@section('title', 'Supplier Details')
@section('page-title', 'Supplier Details')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('erp.suppliers.index') }}" class="btn btn-secondary">Back to Suppliers</a>
            <a href="{{ route('erp.suppliers.ledger', $supplier) }}" class="btn btn-info">Ledger</a>
            <a href="{{ route('erp.suppliers.materials-supplied', $supplier) }}" class="btn btn-success">Materials Supplied</a>
            <a href="{{ route('erp.suppliers.create-payment', $supplier) }}" class="btn btn-primary">Record Payment</a>
            <a href="{{ route('erp.suppliers.edit', $supplier) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Purchases</h6>
                    <h3>{{ $dashboard['total_purchases'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Amount</h6>
                    <h3>{{ number_format($dashboard['total_amount_purchased'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Paid</h6>
                    <h3>{{ number_format($dashboard['total_paid'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Balance</h6>
                    <h3>{{ number_format($dashboard['remaining_balance'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h6 class="card-title">Materials</h6>
                    <h3>{{ $dashboard['materials_supplied'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h6 class="card-title">Last Purchase</h6>
                    <h5>{{ $dashboard['last_purchase_date'] ?? '—' }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Supplier Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $supplier->supplier_code }}</p>
                    <p><strong>Company:</strong> {{ $supplier->company_name }}</p>
                    <p><strong>Contact Person:</strong> {{ $supplier->contact_person ?? '—' }}</p>
                    <p><strong>Phone:</strong> {{ $supplier->phone ?? '—' }}</p>
                    <p><strong>WhatsApp:</strong> {{ $supplier->whatsapp ?? '—' }}</p>
                    <p><strong>Email:</strong> {{ $supplier->email ?? '—' }}</p>
                    <p><strong>Address:</strong> {{ $supplier->address ?? '—' }}</p>
                    <p><strong>City:</strong> {{ $supplier->city ?? '—' }}</p>
                    <p><strong>Status:</strong> <span class="badge badge-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}">{{ $supplier->status }}</span></p>
                    <p><strong>Notes:</strong> {{ $supplier->notes ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Purchases</h3>
                </div>
                <div class="card-body">
                    @if ($dashboard['recent_purchases']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dashboard['recent_purchases'] as $order)
                                        <tr>
                                            <td>{{ $order->purchase_number }}</td>
                                            <td>{{ $order->purchase_date }}</td>
                                            <td>{{ number_format($order->grand_total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No recent purchases found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
