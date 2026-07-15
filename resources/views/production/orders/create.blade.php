@extends('layouts.app')

@section('title', 'Create Production Order')
@section('page-title', 'Create Production Order')

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                    @if(session('stock_issues'))
                        <ul class="mt-2">
                            @foreach(session('stock_issues') as $issue)
                                <li>{{ $issue['material'] }}: Required {{ number_format($issue['required'], 2) }}, Available {{ number_format($issue['available'], 2) }}, Shortage {{ number_format($issue['shortage'], 2) }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
            
            <form method="POST" action="{{ route('erp.production.orders.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Product</label>
                        <select name="product_id" class="form-control" required id="product-select">
                            <option value="">Select product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-formula="{{ $product->productionFormula ? 'yes' : 'no' }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted" id="formula-status"></small>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Quantity to Produce</label>
                        <input type="number" step="0.01" name="quantity_to_produce" class="form-control" required id="quantity-input">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Production Date</label>
                        <input type="date" name="production_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                </div>
                
                <div id="formula-preview" class="mt-3" style="display: none;">
                    <hr>
                    <h5>Formula Preview</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Required per Unit</th>
                                    <th>Available Stock</th>
                                    <th>Total Required</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="formula-items">
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Create Order</button>
                <a href="{{ route('erp.production.orders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
    
    <script>
        const formulas = @json($products->map(fn($p) => ['id' => $p->id, 'formula' => $p->productionFormula?->load('items.rawMaterial')]));
        
        document.getElementById('product-select').addEventListener('change', function() {
            const productId = this.value;
            const formulaData = formulas.find(f => f.id == productId);
            const formulaPreview = document.getElementById('formula-preview');
            const formulaStatus = document.getElementById('formula-status');
            const formulaItems = document.getElementById('formula-items');
            
            if (formulaData && formulaData.formula) {
                formulaStatus.textContent = 'Formula available';
                formulaStatus.className = 'text-success';
                formulaPreview.style.display = 'block';
                
                formulaItems.innerHTML = '';
                formulaData.formula.items.forEach(item => {
                    const row =document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.rawMaterial.name}</td>
                        <td>${item.quantity_required} ${item.unit || item.rawMaterial.unit}</td>
                        <td>${item.rawMaterial.current_stock} ${item.rawMaterial.unit}</td>
                        <td id="required-${item.rawMaterial.id}">-</td>
                        <td id="status-${item.rawMaterial.id}">-</td>
                    `;
                    formulaItems.appendChild(row);
                });
            } else {
                formulaStatus.textContent = 'No formula available for this product';
                formulaStatus.className = 'text-danger';
                formulaPreview.style.display = 'none';
            }
        });
        
        document.getElementById('quantity-input').addEventListener('input', function() {
            const quantity = parseFloat(this.value) || 0;
            const productId = document.getElementById('product-select').value;
            const formulaData = formulas.find(f => f.id == productId);
            
            if (formulaData && formulaData.formula) {
                formulaData.formula.items.forEach(item => {
                    const required = item.quantity_required * quantity;
                    const available = item.rawMaterial.current_stock;
                    const requiredCell = document.getElementById(`required-${item.rawMaterial.id}`);
                    const statusCell = document.getElementById(`status-${item.rawMaterial.id}`);
                    
                    if (requiredCell && statusCell) {
                        requiredCell.textContent = `${required.toFixed(2)} ${item.unit || item.rawMaterial.unit}`;
                        
                        if (available >= required) {
                            statusCell.textContent = 'Sufficient';
                            statusCell.className = 'text-success';
                        } else {
                            statusCell.textContent = `Insufficient (Shortage: ${(required - available).toFixed(2)})`;
                            statusCell.className = 'text-danger';
                        }
                    }
                });
            }
        });
    </script>
@endsection
