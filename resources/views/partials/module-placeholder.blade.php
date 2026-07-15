<div class="card erp-card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-layer-group mr-2"></i>{{ $pageTitle ?? 'Module' }}</h3>
    </div>
    <div class="card-body">
        <div class="erp-placeholder">
            <i class="fas fa-cubes"></i>
            <h5>{{ $pageTitle ?? 'Module' }}</h5>
            <p class="text-muted mb-0">This module shell is ready for the next implementation phase. The navigation, layout, and page structure are in place.</p>
            <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#moduleInfoModal">
                <i class="fas fa-info-circle mr-1"></i> View Module Notes
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="moduleInfoModal" tabindex="-1" role="dialog" aria-labelledby="moduleInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moduleInfoModalLabel">Module Shell</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>This placeholder page inherits the shared ERP layout and is ready for data tables, forms, and CRUD views.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
