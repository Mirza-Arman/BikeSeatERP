@extends('layouts.app')

@section('title', 'Raw Material Details')
@section('page-title', 'Raw Material Details')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Material Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $rawMaterial->material_code }}</p>
                    <p><strong>Name:</strong> {{ $rawMaterial->name }}</p>
                    <p><strong>Category:</strong> {{ $rawMaterial->category?->name ?? '—' }}</p>
                    <p><strong>Unit:</strong> {{ $rawMaterial->unit ?? '—' }}</p>
                    <p><strong>Current Stock:</strong> {{ number_format($rawMaterial->current_stock, 2) }} {{ $rawMaterial->unit ?? '' }}</p>
                    <p><strong>Minimum Stock:</strong> {{ number_format($rawMaterial->minimum_stock, 2) }} {{ $rawMaterial->unit ?? '' }}</p>
                    <p><strong>Cost per Unit:</strong> {{ number_format($rawMaterial->cost_per_unit, 2) }}</p>
                    <p><strong>Status:</strong> {{ $rawMaterial->status }}</p>
                    <p><strong>Description:</strong> {{ $rawMaterial->description ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Stock Transactions</h3>
                </div>
                <div class="card-body">
                    @if ($rawMaterial->stockTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Balance</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rawMaterial->stockTransactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->transaction_type }}</td>
                                            <td>{{ number_format($transaction->quantity, 2) }}</td>
                                            <td>{{ number_format($transaction->balance_after, 2) }}</td>
                                            <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
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
@endsection
