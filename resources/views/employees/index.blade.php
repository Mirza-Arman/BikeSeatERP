@extends('layouts.app')

@section('title', 'Employees')
@section('page-title', 'Employees')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Employee directory</h3>
            <a href="{{ route('erp.employees.create') }}" class="btn btn-primary btn-sm">New employee</a>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <input type="text" name="search" class="form-control mr-2" value="{{ request('search') }}" placeholder="Search employee">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </form>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_code }}</td>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->department?->name ?? '—' }}</td>
                            <td><span class="badge badge-{{ $employee->status === 'active' ? 'success' : 'secondary' }}">{{ $employee->status }}</span></td>
                            <td>
                                <a href="{{ route('erp.employees.show', $employee) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('erp.employees.edit', $employee) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('erp.employees.toggle-status', $employee) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-{{ $employee->status === 'active' ? 'secondary' : 'success' }}" type="submit">
                                        {{ $employee->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('erp.employees.destroy', $employee) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No employees found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $employees->links() }}
        </div>
    </div>
@endsection
