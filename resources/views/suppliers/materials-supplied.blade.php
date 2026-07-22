@extends('layouts.app')

@section('title', 'Materials Supplied')
@section('page-title', 'Materials Supplied')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('erp.suppliers.show', $supplier) }}" class="btn btn-secondary">Back to Supplier</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Materials Supplied - {{ $supplier->company_name }}</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Model</th>
                            <th>Quality</th>
                            <th>Unit</th>
                            <th>Last Price</th>
                            <th>Last Purchase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materials as $material)
                            <tr>
                                <td>{{ $material['material'] }}</td>
                                <td>{{ $material['model'] }}</td>
                                <td>{{ $material['quality'] }}</td>
                                <td>{{ $material['unit'] }}</td>
                                <td>{{ number_format($material['last_price'], 2) }}</td>
                                <td>{{ $material['last_purchase'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No materials supplied yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
