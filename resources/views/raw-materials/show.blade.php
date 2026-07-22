@extends('layouts.app')

@section('title', 'Raw Material Details')
@section('page-title', 'Raw Material Details')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('erp.raw-materials.index') }}" class="btn btn-secondary">Back to Materials</a>
            <a href="{{ route('erp.raw-materials.edit', $rawMaterial) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Material Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $rawMaterial->material_code }}</p>
                    <p><strong>Name:</strong> {{ $rawMaterial->name }}</p>
                    <p><strong>Category:</strong> {{ $rawMaterial->category?->name ?? '—' }}</p>
                    <p><strong>Supplier:</strong> {{ $rawMaterial->supplier?->company_name ?? '—' }}</p>
                    @if (is_array($rawMaterial->attributes) && count($rawMaterial->attributes) > 0)
                        <p><strong>Attributes:</strong><br>
                        @foreach ($rawMaterial->attributes as $key => $value)
                            @if ($value){{ ucfirst($key) }}: {{ $value }}<br>@endif
                        @endforeach
                        </p>
                    @endif
                    <p><strong>Unit:</strong> {{ $rawMaterial->unit ?? '—' }}</p>
                    <p><strong>Current Stock:</strong> {{ number_format($rawMaterial->current_stock, 2) }} {{ $rawMaterial->unit ?? '' }}</p>
                    <p><strong>Minimum Stock:</strong> {{ number_format($rawMaterial->minimum_stock, 2) }} {{ $rawMaterial->unit ?? '' }}</p>
                    <p><strong>Purchase Price:</strong> {{ number_format($rawMaterial->purchase_price, 2) }}</p>
                    <p><strong>Average Cost:</strong> {{ number_format($rawMaterial->average_cost, 2) }}</p>
                    <p><strong>Status:</strong> <span class="badge badge-{{ $rawMaterial->status === 'active' ? 'success' : 'secondary' }}">{{ $rawMaterial->status }}</span></p>
                    <p><strong>Description:</strong> {{ $rawMaterial->description ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Stock Movement</h3>
                </div>
                <div class="card-body">
                    @if ($detail['stock_movement']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Qty</th>
                                        <th>New Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail['stock_movement'] as $transaction)
                                        <tr>
                                            <td>{{ $transaction['date']->format('Y-m-d') }}</td>
                                            <td>{{ $transaction['type'] }}</td>
                                            <td>{{ number_format($transaction['quantity'], 2) }}</td>
                                            <td>{{ number_format($transaction['new_quantity'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No stock transactions found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Purchase History</h3>
                </div>
                <div class="card-body">
                    @if ($detail['supplier_history']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Supplier</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail['supplier_history'] as $purchase)
                                        <tr>
                                            <td>{{ $purchase['date'] }}</td>
                                            <td>{{ $purchase['supplier'] }}</td>
                                            <td>{{ number_format($purchase['quantity'], 2) }}</td>
                                            <td>{{ number_format($purchase['unit_price'], 2) }}</td>
                                            <td>{{ number_format($purchase['total'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No purchase history found.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Price History</h3>
                </div>
                <div class="card-body">
                    @if ($detail['price_history']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Price</th>
                                        <th>Supplier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail['price_history'] as $price)
                                        <tr>
                                            <td>{{ $price['date'] }}</td>
                                            <td>{{ number_format($price['price'], 2) }}</td>
                                            <td>{{ $price['supplier'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No price history found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
