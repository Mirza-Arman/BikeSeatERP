@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Customer directory</h3>
            <a href="{{ route('erp.customers.create') }}" class="btn btn-primary btn-sm">New customer</a>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <input type="text" name="search" class="form-control mr-2" value="{{ request('search') }}" placeholder="Search customer">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </form>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->customer_code }}</td>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->balance }}</td>
                            <td>
                                <a href="{{ route('erp.customers.show', $customer) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('erp.customers.edit', $customer) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('erp.customers.toggle-status', $customer) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-{{ $customer->status === 'active' ? 'secondary' : 'success' }}" type="submit">
                                        {{ $customer->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('erp.customers.destroy', $customer) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No customers found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $customers->links() }}
        </div>
    </div>
@endsection
