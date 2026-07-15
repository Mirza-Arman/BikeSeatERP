@extends('layouts.app')

@section('title', 'Edit Production Formula')
@section('page-title', 'Edit Production Formula')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('erp.production.formula.update', $formula) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Product</label>
                        <select name="product_id" class="form-control" required>
                            <option value="">Select product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ $formula->product_id == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Version</label>
                        <input type="text" name="version" class="form-control" value="{{ $formula->version }}" required>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control">{{ $formula->description ?? '' }}</textarea>
                    </div>
                </div>
                
                <hr>
                <h5>Formula Items (Raw Materials)</h5>
                <div id="items-container">
                    @foreach ($formula->items as $index => $item)
                        <div class="row item-row mb-2">
                            <div class="col-md-4">
                                <label>Raw Material</label>
                                <select name="items[{{ $index }}][raw_material_id]" class="form-control" required>
                                    <option value="">Select material</option>
                                    @foreach ($rawMaterials as $material)
                                        <option value="{{ $material->id }}" {{ $item->raw_material_id == $material->id ? 'selected' : '' }}>{{ $material->name }} ({{ $material->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Quantity Required</label>
                                <input type="number" step="0.01" name="items[{{ $index }}][quantity_required]" class="form-control" value="{{ $item->quantity_required }}" required>
                            </div>
                            <div class="col-md-3">
                                <label>Unit</label>
                                <input type="text" name="items[{{ $index }}][unit]" class="form-control" value="{{ $item->unit }}">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary btn-sm mb-3" id="add-item">Add Material</button>
                
                <button type="submit" class="btn btn-primary">Update Formula</button>
                <a href="{{ route('erp.production.formula.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
    
    <script>
        let itemCount = {{ $formula->items->count() }};
        
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'row item-row mb-2';
            newRow.innerHTML = `
                <div class="col-md-4">
                    <label>Raw Material</label>
                    <select name="items[${itemCount}][raw_material_id]" class="form-control" required>
                        <option value="">Select material</option>
                        @foreach ($rawMaterials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Quantity Required</label>
                    <input type="number" step="0.01" name="items[${itemCount}][quantity_required]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Unit</label>
                    <input type="text" name="items[${itemCount}][unit]" class="form-control">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                </div>
            `;
            container.appendChild(newRow);
            itemCount++;
        });
        
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item-row').remove();
            }
        });
    </script>
@endsection
