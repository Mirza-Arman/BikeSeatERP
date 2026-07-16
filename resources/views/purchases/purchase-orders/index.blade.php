@extends('layouts.app')

@section('title', 'Purchase Orders')
@section('page-title', 'Purchase Orders')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Purchase Orders</h3>
            <a href="{{ route('purchases.purchase-orders.create') }}" class="btn btn-primary btn-sm">New Purchase Order</a>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <input type="text" name="search" class="form-control mr-2" value="{{ request('search') }}" placeholder="Search PO number, invoice, supplier">
                <select name="supplier_id" class="form-control mr-2">
                    <option value="">All Suppliers</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-control mr-2">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <select name="payment_status" class="form-control mr-2">
                    <option value="">All Payment Status</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
                <input type="date" name="date_from" class="form-control mr-2" value="{{ request('date_from') }}" placeholder="From">
                <input type="date" name="date_to" class="form-control mr-2" value="{{ request('date_to') }}" placeholder="To">
                <button type="submit" class="btn btn-outline-secondary">Filter</button>
                <a href="{{ route('purchases.purchase-orders.index') }}" class="btn btn-link">Clear</a>
            </form>

            <div class="row mb-3">
                <div class="col-md-2">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Today's Purchases</h5>
                            <h3>{{ number_format($statistics['today_purchases'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Monthly Purchases</h5>
                            <h3>{{ number_format($statistics['monthly_purchases'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Pending Orders</h5>
                            <h3>{{ $statistics['pending_orders'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Completed Orders</h5>
                            <h3>{{ $statistics['completed_orders'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">Outstanding</h5>
                            <h3>{{ number_format($statistics['supplier_outstanding'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Overdue</h5>
                            <h3>{{ $statistics['overdue_orders'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchaseOrders as $order)
                            <tr>
                                <td>{{ $order->purchase_number }}</td>
                                <td>{{ $order->purchase_date->format('Y-m-d') }}</td>
                                <td>{{ $order->supplier->company_name }}</td>
                                <td>{{ number_format($order->grand_total, 2) }}</td>
                                <td><span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'partial' ? 'warning' : 'secondary') }}">{{ $order->status }}</span></td>
                                <td><span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'partial' ? 'warning' : 'danger') }}">{{ $order->payment_status }}</span></td>
                                <td>
                                    <a href="{{ route('purchases.purchase-orders.show', $order) }}" class="btn btn-sm btn-info">View</a>
                                    @if ($order->status !== 'completed')
                                        <a href="{{ route('purchases.purchase-orders.edit', $order) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @endif
                                    <form action="{{ route('purchases.purchase-orders.destroy', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No purchase orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $purchaseOrders->links() }}
        </div>
    </div>
@endsection
