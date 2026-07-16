@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        @foreach($cards as $card)
            <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-4">
                <div class="small-box erp-stat-card {{ $card['class'] }}">
                    <div class="inner">
                        <h3>{{ $card['value'] }}</h3>
                        <p>{{ $card['title'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="{{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card erp-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Production Overview</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12 col-md-4">
                            <div class="erp-summary-box">
                                <div class="erp-summary-title">Today Production</div>
                                <div class="erp-summary-value">{{ $overview['today'] }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="erp-summary-box">
                                <div class="erp-summary-title">Pending Orders</div>
                                <div class="erp-summary-value">{{ $overview['pending'] }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="erp-summary-box">
                                <div class="erp-summary-title">Completed Orders</div>
                                <div class="erp-summary-value">{{ $overview['completed'] }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            @php
                                $productionTotal = max(1, $overview['pending'] + $overview['in_progress'] + $overview['completed']);
                            @endphp
                            <div class="progress mb-3" style="height: 18px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $overview['pending'] > 0 ? min(100, ($overview['pending'] / $productionTotal) * 100) : 0 }}%" aria-valuenow="{{ $overview['pending'] }}" aria-valuemin="0" aria-valuemax="100">Pending</div>
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $overview['in_progress'] > 0 ? min(100, ($overview['in_progress'] / $productionTotal) * 100) : 0 }}%" aria-valuenow="{{ $overview['in_progress'] }}" aria-valuemin="0" aria-valuemax="100">In Progress</div>
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $overview['completed'] > 0 ? min(100, ($overview['completed'] / $productionTotal) * 100) : 0 }}%" aria-valuenow="{{ $overview['completed'] }}" aria-valuemin="0" aria-valuemax="100">Completed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card erp-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-2"></i>Recent Activities</h3>
                </div>
                <div class="card-body">
                    @forelse($activities as $activity)
                        <div class="erp-activity-item">
                            <h6 class="mb-1">{{ $activity['title'] }}</h6>
                            <p class="mb-1 small text-muted">{{ $activity['description'] }}</p>
                            <small class="text-muted">{{ $activity['time'] }}</small>
                        </div>
                    @empty
                        <div class="erp-activity-item">
                            <h6 class="mb-1">No recent activity available</h6>
                            <small class="text-muted">Waiting for ERP events to appear.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card erp-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-table mr-2"></i>Module Status</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover erp-table erp-datatable mb-0">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Production</td><td><span class="badge badge-success">Ready</span></td><td><a href="{{ route('erp.production.orders.index') }}" class="btn btn-sm btn-outline-primary">Open</a></td></tr>
                            <tr><td>Inventory</td><td><span class="badge badge-success">Ready</span></td><td><a href="{{ route('erp.inventory.stock.index') }}" class="btn btn-sm btn-outline-primary">Open</a></td></tr>
                            <tr><td>Suppliers</td><td><span class="badge badge-success">Ready</span></td><td><a href="{{ route('erp.suppliers.index') }}" class="btn btn-sm btn-outline-primary">Open</a></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card erp-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-edit mr-2"></i>ERP Form Preview</h3>
                </div>
                <div class="card-body erp-form">
                    <form action="#" method="POST" onsubmit="return false;">
                        <div class="form-group">
                            <label class="form-label">Production Batch</label>
                            <input type="text" class="form-control" placeholder="Enter batch reference...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control">
                                <option>Planned</option>
                                <option>In Progress</option>
                                <option>Completed</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sampleModal">Open Modal</button>
                            <button type="button" class="btn btn-warning" onclick="erpNotify('success', 'Toast notification sample')">Show Toast</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sampleModal" tabindex="-1" role="dialog" aria-labelledby="sampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sampleModalLabel">Module Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Bootstrap modals are ready for future CRUD dialogs and workflow confirmations.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection
