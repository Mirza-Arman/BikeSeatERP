@extends('layouts.app')

@section('title', 'Create Customer')
@section('page-title', 'Create Customer')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('erp.customers.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Customer code</label>
                        <input type="text" name="customer_code" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Customer name</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('erp.customers.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
