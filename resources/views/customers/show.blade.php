@extends('layouts.app')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $customer->customer_code }}</p>
                    <p><strong>Name:</strong> {{ $customer->customer_name }}</p>
                    <p><strong>Phone:</strong> {{ $customer->phone ?? '—' }}</p>
                    <p><strong>Email:</strong> {{ $customer->email ?? '—' }}</p>
                    <p><strong>Address:</strong> {{ $customer->address ?? '—' }}</p>
                    <p><strong>City:</strong> {{ $customer->city ?? '—' }}</p>
                    <p><strong>Outstanding Balance:</strong> {{ number_format($customer->balance, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Orders</h3>
                </div>
                <div class="card-body">
                    @if ($customer->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order No</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer->orders as $order)
                                        <tr>
                                            <td>{{ $order->invoice_no }}</td>
                                            <td>{{ $order->order_date }}</td>
                                            <td>{{ number_format($order->grand_total, 2) }}</td>
                                            <td><span class="badge badge-{{ $order->status === 'completed' ? 'success' : 'warning' }}">{{ $order->status }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No orders found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
