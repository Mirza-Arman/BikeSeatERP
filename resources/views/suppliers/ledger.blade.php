@extends('layouts.app')

@section('title', 'Supplier Ledger')
@section('page-title', 'Supplier Ledger')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('erp.suppliers.show', $supplier) }}" class="btn btn-secondary">Back to Supplier</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ledger - {{ $supplier->company_name }}</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Material</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ledger as $entry)
                            <tr>
                                <td>{{ $entry['date'] }}</td>
                                <td>{{ $entry['reference_number'] }}</td>
                                <td>{{ $entry['material_purchased'] }}</td>
                                <td class="text-danger">{{ $entry['debit'] > 0 ? number_format($entry['debit'], 2) : '—' }}</td>
                                <td class="text-success">{{ $entry['credit'] > 0 ? number_format($entry['credit'], 2) : '—' }}</td>
                                <td><strong>{{ number_format($entry['balance'], 2) }}</strong></td>
                                <td>{{ $entry['remarks'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
