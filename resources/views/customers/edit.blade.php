@extends('layouts.app')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('erp.customers.update', $customer) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Customer code</label>
                        <input type="text" name="customer_code" class="form-control" value="{{ old('customer_code', $customer->customer_code) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Customer name</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $customer->customer_name) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $customer->city) }}">
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control">{{ old('address', $customer->address) }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('erp.customers.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
