@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Product catalog</h3>
            <a href="{{ route('erp.inventory.products.create') }}" class="btn btn-primary btn-sm">New product</a>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <input type="text" name="search" class="form-control mr-2" value="{{ request('search') }}" placeholder="Search product">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </form>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ number_format($product->current_stock, 2) }}</td>
                            <td>{{ number_format($product->selling_price, 2) }}</td>
                            <td><span class="badge badge-{{ $product->status === 'active' ? 'success' : 'secondary' }}">{{ $product->status }}</span></td>
                            <td>
                                <a href="{{ route('erp.inventory.products.show', $product) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('erp.inventory.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('erp.inventory.products.toggle-status', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-{{ $product->status === 'active' ? 'secondary' : 'success' }}" type="submit">
                                        {{ $product->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('erp.inventory.products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No products found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
        </div>
    </div>
@endsection
