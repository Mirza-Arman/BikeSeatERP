@extends('layouts.app')

@section('title', 'Purchase Order Details')
@section('page-title', 'Purchase Order Details')

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Invoice:</strong> {{ $purchaseOrder->invoice_no }}</p>
            <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier?->company_name ?? '—' }}</p>
            <p><strong>Date:</strong> {{ $purchaseOrder->purchase_date }}</p>
            <p><strong>Status:</strong> {{ $purchaseOrder->status }}</p>
        </div>
    </div>
@endsection
