@extends('layouts.app')

@section('title', 'Production Orders')
@section('page-title', 'Production Orders')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Production orders</h3>
            <a href="{{ route('erp.production.orders.create') }}" class="btn btn-primary btn-sm">New order</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Order</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($productionOrders as $productionOrder)
                        <tr>
                            <td>{{ $productionOrder->order_no }}</td>
                            <td>{{ $productionOrder->product?->product_name ?? '—' }}</td>
                            <td>{{ number_format($productionOrder->quantity_to_produce, 2) }}</td>
                            <td>{{ $productionOrder->status }}</td>
                            <td>
                                <a href="{{ route('erp.production.orders.show', $productionOrder) }}" class="btn btn-sm btn-info">View</a>
                                <form action="{{ route('erp.production.orders.status', $productionOrder) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button class="btn btn-sm btn-success" type="submit">Complete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No production orders found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $productionOrders->links() }}
        </div>
    </div>
@endsection
