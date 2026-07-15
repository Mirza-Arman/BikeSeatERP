@extends('layouts.app')

@section('title', 'Product Details')
@section('page-title', 'Product Details')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $product->product_code }}</p>
                    <p><strong>Name:</strong> {{ $product->product_name }}</p>
                    <p><strong>Model:</strong> {{ $product->model ?? '—' }}</p>
                    <p><strong>Selling Price:</strong> {{ number_format($product->selling_price, 2) }}</p>
                    <p><strong>Current Stock:</strong> {{ number_format($product->current_stock, 2) }}</p>
                    <p><strong>Minimum Stock:</strong> {{ number_format($product->minimum_stock, 2) }}</p>
                    <p><strong>Status:</strong> {{ $product->status }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Transactions</h3>
                </div>
                <div class="card-body">
                    @if ($product->finishedGoodsTransactions->count() > 0)
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
                                    @foreach ($product->finishedGoodsTransactions as $transaction)
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
                        <p class="text-muted">No transactions found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
