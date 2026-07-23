@extends('layouts.modern')

@section('title', 'Create Customer Order')
@section('page-title', 'Create Customer Order')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('erp.customers.orders.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Customer</label>
                        <select name="customer_id" class="form-control" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Invoice number</label>
                        <input type="text" name="invoice_no" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Order date</label>
                        <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Items</label>
                        <textarea name="items" class="form-control" placeholder="JSON array of items"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('erp.customers.orders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
