@extends('layouts.app')

@section('title', 'Supplier Details')
@section('page-title', 'Supplier Details')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Supplier Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $supplier->supplier_code }}</p>
                    <p><strong>Company:</strong> {{ $supplier->company_name }}</p>
                    <p><strong>Contact person:</strong> {{ $supplier->contact_person ?? '—' }}</p>
                    <p><strong>Phone:</strong> {{ $supplier->phone ?? '—' }}</p>
                    <p><strong>Email:</strong> {{ $supplier->email ?? '—' }}</p>
                    <p><strong>Address:</strong> {{ $supplier->address ?? '—' }}</p>
                    <p><strong>City:</strong> {{ $supplier->city ?? '—' }}</p>
                    <p><strong>Status:</strong> {{ $supplier->status }}</p>
                    <p><strong>Current Balance:</strong> {{ number_format($supplier->balance, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Purchase Orders</h3>
                </div>
                <div class="card-body">
                    @if ($supplier->purchaseOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supplier->purchaseOrders as $order)
                                        <tr>
                                            <td>{{ $order->invoice_no }}</td>
                                            <td>{{ $order->order_date }}</td>
                                            <td>{{ number_format($order->total_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No purchase orders found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
