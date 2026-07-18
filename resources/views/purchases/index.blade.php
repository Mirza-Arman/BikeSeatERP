@extends('layouts.app')

@section('title', 'Purchase Orders')
@section('page-title', 'Purchase Orders')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Purchase orders</h3>
            <a href="{{ route('purchases.purchase-orders.create') }}" class="btn btn-primary btn-sm">New purchase order</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Supplier</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($purchaseOrders as $purchaseOrder)
                        <tr>
                            <td>{{ $purchaseOrder->invoice_no }}</td>
                            <td>{{ $purchaseOrder->supplier?->company_name ?? '—' }}</td>
                            <td>{{ $purchaseOrder->purchase_date }}</td>
                            <td>{{ number_format($purchaseOrder->grand_total, 2) }}</td>
                            <td>{{ number_format($purchaseOrder->paid_amount, 2) }}</td>
                            <td>{{ number_format($purchaseOrder->remaining_amount, 2) }}</td>
                            <td><span class="badge badge-{{ $purchaseOrder->status === 'received' ? 'success' : 'warning' }}">{{ $purchaseOrder->status }}</span></td>
                            <td>
                                <a href="{{ route('purchases.purchase-orders.show', $purchaseOrder) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('purchases.purchase-orders.edit', $purchaseOrder) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('purchases.purchase-orders.destroy', $purchaseOrder) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No purchase orders found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $purchaseOrders->links() }}
        </div>
    </div>
@endsection
