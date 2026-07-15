@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        @foreach([
            ['title' => 'Total Employees', 'value' => '128', 'icon' => 'fas fa-users', 'class' => 'bg-primary-gradient'],
            ['title' => 'Total Suppliers', 'value' => '34', 'icon' => 'fas fa-truck-loading', 'class' => 'bg-success-gradient'],
            ['title' => 'Total Customers', 'value' => '812', 'icon' => 'fas fa-user-friends', 'class' => 'bg-info-gradient'],
            ['title' => 'Raw Materials', 'value' => '96', 'icon' => 'fas fa-cubes', 'class' => 'bg-warning-gradient'],
            ['title' => 'Products', 'value' => '245', 'icon' => 'fas fa-chair', 'class' => 'bg-purple-gradient'],
            ['title' => 'Production Today', 'value' => '186', 'icon' => 'fas fa-industry', 'class' => 'bg-teal-gradient'],
            ['title' => 'Low Stock', 'value' => '12', 'icon' => 'fas fa-exclamation-triangle', 'class' => 'bg-danger-gradient'],
            ['title' => 'Purchase Orders', 'value' => '21', 'icon' => 'fas fa-file-invoice', 'class' => 'bg-dark-gradient'],
            ['title' => 'Customer Orders', 'value' => '59', 'icon' => 'fas fa-shopping-cart', 'class' => 'bg-primary-gradient'],
        ] as $card)
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
                    <div class="erp-placeholder">
                        <i class="fas fa-chart-bar"></i>
                        <p>Chart widgets and analytics will be added here once the reporting layer is configured.</p>
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
                    <div class="erp-activity-item">
                        <h6 class="mb-1">Production order completed</h6>
                        <small class="text-muted">10 minutes ago</small>
                    </div>
                    <div class="erp-activity-item">
                        <h6 class="mb-1">New supplier approved</h6>
                        <small class="text-muted">36 minutes ago</small>
                    </div>
                    <div class="erp-activity-item">
                        <h6 class="mb-1">Low stock alert triggered</h6>
                        <small class="text-muted">1 hour ago</small>
                    </div>
                    <div class="erp-activity-item">
                        <h6 class="mb-1">Customer order updated</h6>
                        <small class="text-muted">2 hours ago</small>
                    </div>
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
                            <tr><td>Production</td><td><span class="badge badge-success">Ready</span></td><td><a href="{{ route('production.orders.index') }}" class="btn btn-sm btn-outline-primary">Open</a></td></tr>
                            <tr><td>Inventory</td><td><span class="badge badge-success">Ready</span></td><td><a href="{{ route('inventory.stock.index') }}" class="btn btn-sm btn-outline-primary">Open</a></td></tr>
                            <tr><td>Suppliers</td><td><span class="badge badge-success">Ready</span></td><td><a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-primary">Open</a></td></tr>
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
