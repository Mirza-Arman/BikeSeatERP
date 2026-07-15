@extends('layouts.app')

@section('title', 'Create Product')
@section('page-title', 'Create Product')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('erp.inventory.products.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Product code</label>
                        <input type="text" name="product_code" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Product name</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Model</label>
                        <input type="text" name="model" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Selling price</label>
                        <input type="number" step="0.01" name="selling_price" class="form-control" value="0">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Minimum stock</label>
                        <input type="number" step="0.01" name="minimum_stock" class="form-control" value="0">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Current stock</label>
                        <input type="number" step="0.01" name="current_stock" class="form-control" value="0">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('erp.inventory.products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
