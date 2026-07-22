@extends('layouts.app')

@section('title', 'Edit Raw Material')
@section('page-title', 'Edit Raw Material')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Material</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('erp.raw-materials.update', $rawMaterial) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category *</label>
                            <select name="category_id" class="form-control" id="category-select" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $rawMaterial->category_id) == $category->id ? 'selected' : '' }}
                                        data-attributes='@json($category->attributes)'>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Supplier</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $rawMaterial->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Material Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $rawMaterial->name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Unit *</label>
                            <input type="text" name="unit" class="form-control" value="{{ old('unit', $rawMaterial->unit) }}" required>
                        </div>
                    </div>
                </div>

                <div id="attributes-container" class="row mb-3">
                    @if (is_array($rawMaterial->attributes) && count($rawMaterial->attributes) > 0)
                        @foreach ($rawMaterial->attributes as $key => $value)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ ucfirst($key) }}</label>
                                    <input type="text" name="attributes[{{ $key }}]" class="form-control" value="{{ $value }}">
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Minimum Stock</label>
                            <input type="number" name="minimum_stock" class="form-control" step="0.01" value="{{ old('minimum_stock', $rawMaterial->minimum_stock) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Current Stock</label>
                            <input type="number" name="current_stock" class="form-control" step="0.01" value="{{ old('current_stock', $rawMaterial->current_stock) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Purchase Price</label>
                            <input type="number" name="purchase_price" class="form-control" step="0.01" value="{{ old('purchase_price', $rawMaterial->purchase_price) }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $rawMaterial->description) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ old('status', $rawMaterial->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $rawMaterial->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Material</button>
                <a href="{{ route('erp.raw-materials.show', $rawMaterial) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script>
        const categorySelect = document.getElementById('category-select');
        const attributesContainer = document.getElementById('attributes-container');

        categorySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const attributes = selectedOption.dataset.attributes ? JSON.parse(selectedOption.dataset.attributes) : [];

            attributesContainer.innerHTML = '';

            attributes.forEach(attr => {
                const col = document.createElement('div');
                col.className = 'col-md-4';
                col.innerHTML = `
                    <div class="form-group">
                        <label>${attr.attribute_name} ${attr.is_required ? '*' : ''}</label>
                        <input type="text" name="attributes[${attr.attribute_name}]" class="form-control" ${attr.is_required ? 'required' : ''}>
                    </div>
                `;
                attributesContainer.appendChild(col);
            });
        });
    </script>
@endsection
