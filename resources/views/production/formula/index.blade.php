@extends('layouts.app')

@section('title', 'Production Formula')
@section('page-title', 'Production Formula')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Production Formulas</h3>
            <a href="{{ route('erp.production.formula.create') }}" class="btn btn-primary btn-sm">New Formula</a>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <input type="text" name="search" class="form-control mr-2" value="{{ request('search') }}" placeholder="Search by product">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </form>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Version</th>
                        <th>Items Count</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($formulas as $formula)
                        <tr>
                            <td>{{ $formula->product->product_name }}</td>
                            <td>{{ $formula->version }}</td>
                            <td>{{ $formula->items->count() }}</td>
                            <td>{{ $formula->description ?? '—' }}</td>
                            <td>
                                <a href="{{ route('erp.production.formula.show', $formula) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('erp.production.formula.edit', $formula) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('erp.production.formula.destroy', $formula) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No formulas found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $formulas->links() }}
        </div>
    </div>
@endsection
