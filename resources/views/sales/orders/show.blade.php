@extends('layouts.modern')

@section('title', 'Customer Order Details')
@section('page-title', 'Customer Order Details')

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Invoice:</strong> {{ $customerOrder->invoice_no }}</p>
            <p><strong>Customer:</strong> {{ $customerOrder->customer?->customer_name ?? '—' }}</p>
            <p><strong>Date:</strong> {{ $customerOrder->order_date }}</p>
            <p><strong>Status:</strong> {{ $customerOrder->status }}</p>
        </div>
    </div>
@endsection
