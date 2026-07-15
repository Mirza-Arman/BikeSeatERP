@extends('layouts.app')

@section('title', 'Production Formula Details')
@section('page-title', 'Production Formula Details')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Formula Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Product:</strong> {{ $formula->product->product_name }}</p>
                    <p><strong>Version:</strong> {{ $formula->version }}</p>
                    <p><strong>Description:</strong> {{ $formula->description ?? '—' }}</p>
                    <p><strong>Total Items:</strong> {{ $formula->items->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Formula Items</h3>
                </div>
                <div class="card-body">
                    @if ($formula->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Raw Material</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($formula->items as $item)
                                        <tr>
                                            <td>{{ $item->rawMaterial->name }}</td>
                                            <td>{{ number_format($item->quantity_required, 2) }}</td>
                                            <td>{{ $item->unit ?? $item->rawMaterial->unit }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No formula items found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
