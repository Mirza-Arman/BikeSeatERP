@extends('layouts.app')

@section('title', 'Production Order Details')
@section('page-title', 'Production Order Details')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Order No:</strong> {{ $productionOrder->order_no }}</p>
                    <p><strong>Product:</strong> {{ $productionOrder->product->product_name }}</p>
                    <p><strong>Quantity to Produce:</strong> {{ number_format($productionOrder->quantity_to_produce, 2) }}</p>
                    <p><strong>Production Date:</strong> {{ $productionOrder->production_date }}</p>
                    <p><strong>Status:</strong> <span class="badge badge-{{ $productionOrder->status === 'completed' ? 'success' : ($productionOrder->status === 'in_progress' ? 'warning' : 'secondary') }}">{{ $productionOrder->status }}</span></p>
                    <p><strong>Remarks:</strong> {{ $productionOrder->remarks ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Formula Requirements</h3>
                </div>
                <div class="card-body">
                    @if ($productionOrder->formula)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Required</th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productionOrder->formula->items as $item)
                                        <tr>
                                            <td>{{ $item->rawMaterial->name }}</td>
                                            <td>{{ number_format($item->quantity_required * $productionOrder->quantity_to_produce, 2) }}</td>
                                            <td>{{ $item->unit ?? $item->rawMaterial->unit }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No formula assigned.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Worker Assignment</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('erp.production.orders.assign-workers', $productionOrder) }}">
                        @csrf
                        <div id="workers-container">
                            @foreach ($productionOrder->workers as $index => $worker)
                                <div class="row worker-row mb-2">
                                    <div class="col-md-4">
                                        <label>Employee</label>
                                        <select name="workers[{{ $index }}][employee_id]" class="form-control" required>
                                            <option value="">Select employee</option>
                                            @foreach (\App\Models\Employee::where('status', 'active')->get() as $employee)
                                                <option value="{{ $employee->id }}" {{ $worker->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Assigned Work</label>
                                        <input type="text" name="workers[{{ $index }}][assigned_work]" class="form-control" value="{{ $worker->assigned_work }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Completed Quantity</label>
                                        <input type="number" step="0.01" name="workers[{{ $index }}][completed_quantity]" class="form-control" value="{{ $worker->completed_quantity }}">
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm remove-worker">Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mb-3" id="add-worker">Add Worker</button>
                        <button type="submit" class="btn btn-primary">Save Workers</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    @if ($productionOrder->status === 'pending')
                        <form action="{{ route('erp.production.orders.status', $productionOrder) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="in_progress">
                            <button class="btn btn-warning">Start Production</button>
                        </form>
                    @elseif ($productionOrder->status === 'in_progress')
                        <form action="{{ route('erp.production.orders.status', $productionOrder) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <button class="btn btn-success">Complete Production</button>
                        </form>
                    @endif
                    <a href="{{ route('erp.production.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let workerCount = {{ $productionOrder->workers->count() }};
        
        document.getElementById('add-worker').addEventListener('click', function() {
            const container = document.getElementById('workers-container');
            const newRow = document.createElement('div');
            newRow.className = 'row worker-row mb-2';
            newRow.innerHTML = `
                <div class="col-md-4">
                    <label>Employee</label>
                    <select name="workers[${workerCount}][employee_id]" class="form-control" required>
                        <option value="">Select employee</option>
                        @foreach (\App\Models\Employee::where('status', 'active')->get() as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Assigned Work</label>
                    <input type="text" name="workers[${workerCount}][assigned_work]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Completed Quantity</label>
                    <input type="number" step="0.01" name="workers[${workerCount}][completed_quantity]" class="form-control" value="0">
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm remove-worker">Remove</button>
                </div>
            `;
            container.appendChild(newRow);
            workerCount++;
        });
        
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-worker')) {
                e.target.closest('.worker-row').remove();
            }
        });
    </script>
@endsection
