@extends('layouts.app')

@section('title', 'Customer Orders')
@section('page-title', 'Customer Orders')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Customer orders</h3>
            <a href="{{ route('erp.customers.orders.create') }}" class="btn btn-primary btn-sm">New order</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($customerOrders as $customerOrder)
                        <tr>
                            <td>{{ $customerOrder->invoice_no }}</td>
                            <td>{{ $customerOrder->customer?->customer_name ?? '—' }}</td>
                            <td>{{ $customerOrder->order_date }}</td>
                            <td>{{ $customerOrder->status }}</td>
                            <td>
                                <a href="{{ route('erp.customers.orders.show', $customerOrder) }}" class="btn btn-sm btn-info">View</a>
                                <form action="{{ route('erp.customers.orders.status', $customerOrder) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button class="btn btn-sm btn-success" type="submit">Complete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No customer orders found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $customerOrders->links() }}
        </div>
    </div>
@endsection
