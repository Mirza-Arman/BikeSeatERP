@extends('layouts.app')

@section('title', 'Edit Raw Material')
@section('page-title', 'Edit Raw Material')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('erp.raw-materials.update', $rawMaterial) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Material code</label>
                        <input type="text" name="material_code" class="form-control" value="{{ old('material_code', $rawMaterial->material_code) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $rawMaterial->name) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $rawMaterial->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Unit</label>
                        <input type="text" name="unit" class="form-control" value="{{ old('unit', $rawMaterial->unit) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Current stock</label>
                        <input type="number" step="0.01" name="current_stock" class="form-control" value="{{ old('current_stock', $rawMaterial->current_stock) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Cost per unit</label>
                        <input type="number" step="0.01" name="cost_per_unit" class="form-control" value="{{ old('cost_per_unit', $rawMaterial->cost_per_unit) }}">
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $rawMaterial->description) }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('erp.raw-materials.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
