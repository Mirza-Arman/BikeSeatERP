@extends('layouts.app')

@section('title', 'Supplier Ledger - {{ $supplier->company_name }}')
@section('page-title', 'Supplier Ledger - {{ $supplier->company_name }}')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ $supplier->company_name }}</h3>
            <a href="{{ route('purchases.supplier-ledger.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Purchases</h5>
                            <h3>{{ number_format($summary['total_purchases'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Payments</h5>
                            <h3>{{ number_format($summary['total_payments'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">Outstanding Balance</h5>
                            <h3>{{ number_format($summary['outstanding_balance'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Last Purchase</h5>
                            <h3>{{ $summary['last_purchase_date'] ? $summary['last_purchase_date']->format('Y-m-d') : 'N/A' }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <h4>Transaction History</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Reference</th>
                            <th>Description</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ledger['transactions'] as $transaction)
                            <tr>
                                <td>{{ $transaction['date']->format('Y-m-d') }}</td>
                                <td><span class="badge badge-{{ $transaction['type'] === 'debit' ? 'danger' : 'success' }}">{{ $transaction['type'] }}</span></td>
                                <td>{{ $transaction['reference'] }}</td>
                                <td>{{ $transaction['description'] }}</td>
                                <td>{{ $transaction['debit'] > 0 ? number_format($transaction['debit'], 2) : '-' }}</td>
                                <td>{{ $transaction['credit'] > 0 ? number_format($transaction['credit'], 2) : '-' }}</td>
                                <td><strong>{{ number_format($transaction['balance'], 2) }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
