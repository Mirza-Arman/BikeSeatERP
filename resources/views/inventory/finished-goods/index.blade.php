@extends('layouts.app')

@section('title', 'Finished Goods')
@section('page-title', 'Finished Goods')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Finished goods overview</h3>
            <a href="{{ route('erp.inventory.products.create') }}" class="btn btn-primary btn-sm">New product</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($products ?? [] as $product)
                        <tr>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->current_stock }}</td>
                            <td>
                                <a href="{{ route('erp.inventory.products.show', $product) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No finished goods found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
