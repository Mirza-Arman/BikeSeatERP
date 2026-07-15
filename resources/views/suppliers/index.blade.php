@extends('layouts.app')

@section('title', 'Suppliers')
@section('page-title', 'Suppliers')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Supplier directory</h3>
            <a href="{{ route('erp.suppliers.create') }}" class="btn btn-primary btn-sm">New supplier</a>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <input type="text" name="search" class="form-control mr-2" value="{{ request('search') }}" placeholder="Search supplier">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </form>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Contact person</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->supplier_code }}</td>
                            <td>{{ $supplier->company_name }}</td>
                            <td>{{ $supplier->contact_person }}</td>
                            <td><span class="badge badge-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}">{{ $supplier->status }}</span></td>
                            <td>
                                <a href="{{ route('erp.suppliers.show', $supplier) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('erp.suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('erp.suppliers.toggle-status', $supplier) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-{{ $supplier->status === 'active' ? 'secondary' : 'success' }}" type="submit">
                                        {{ $supplier->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('erp.suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No suppliers found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $suppliers->links() }}
        </div>
    </div>
@endsection
