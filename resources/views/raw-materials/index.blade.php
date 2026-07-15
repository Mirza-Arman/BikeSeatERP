@extends('layouts.app')

@section('title', 'Raw Materials')
@section('page-title', 'Raw Materials')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Raw material inventory</h3>
            <a href="{{ route('erp.raw-materials.create') }}" class="btn btn-primary btn-sm">New material</a>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <input type="text" name="search" class="form-control mr-2" value="{{ request('search') }}" placeholder="Search material">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </form>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($materials as $material)
                        <tr>
                            <td>{{ $material->material_code }}</td>
                            <td>{{ $material->name }}</td>
                            <td>{{ $material->category?->name ?? '—' }}</td>
                            <td>{{ number_format($material->current_stock, 2) }} {{ $material->unit ?? '' }}</td>
                            <td><span class="badge badge-{{ $material->status === 'active' ? 'success' : 'secondary' }}">{{ $material->status }}</span></td>
                            <td>
                                <a href="{{ route('erp.raw-materials.show', $material) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('erp.raw-materials.edit', $material) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('erp.raw-materials.toggle-status', $material) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-{{ $material->status === 'active' ? 'secondary' : 'success' }}" type="submit">
                                        {{ $material->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('erp.raw-materials.destroy', $material) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No raw materials found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $materials->links() }}
        </div>
    </div>
@endsection
