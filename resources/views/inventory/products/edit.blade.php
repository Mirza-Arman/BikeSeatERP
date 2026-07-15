@extends('layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('erp.inventory.products.update', $product) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Product code</label>
                        <input type="text" name="product_code" class="form-control" value="{{ old('product_code', $product->product_code) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Product name</label>
                        <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Model</label>
                        <input type="text" name="model" class="form-control" value="{{ old('model', $product->model) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Selling price</label>
                        <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price', $product->selling_price) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Minimum stock</label>
                        <input type="number" step="0.01" name="minimum_stock" class="form-control" value="{{ old('minimum_stock', $product->minimum_stock) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Current stock</label>
                        <input type="number" step="0.01" name="current_stock" class="form-control" value="{{ old('current_stock', $product->current_stock) }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('erp.inventory.products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
