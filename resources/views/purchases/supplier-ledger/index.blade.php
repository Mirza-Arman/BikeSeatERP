@extends('layouts.app')

@section('title', 'Supplier Ledger')
@section('page-title', 'Supplier Ledger - Outstanding Balances')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Supplier Outstanding Balances</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Supplier Code</th>
                            <th>Company Name</th>
                            <th>Total Purchases</th>
                            <th>Total Payments</th>
                            <th>Outstanding Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliersOutstanding as $supplier)
                            <tr>
                                <td>{{ $supplier['supplier_code'] }}</td>
                                <td>{{ $supplier['company_name'] }}</td>
                                <td>{{ number_format($supplier['total_purchases'], 2) }}</td>
                                <td>{{ number_format($supplier['total_payments'], 2) }}</td>
                                <td><strong>{{ number_format($supplier['outstanding_balance'], 2) }}</strong></td>
                                <td>
                                    <a href="{{ route('purchases.supplier-ledger.show', $supplier['id']) }}" class="btn btn-sm btn-info">View Ledger</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No outstanding balances found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
