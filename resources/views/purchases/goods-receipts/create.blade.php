@extends('layouts.modern')

@section('title', 'Receive Goods')
@section('page-title', 'Receive Goods - {{ $purchaseOrder->purchase_number }}')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Receive Goods for {{ $purchaseOrder->purchase_number }}</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->company_name }}</p>
                    <p><strong>Purchase Date:</strong> {{ $purchaseOrder->purchase_date->format('Y-m-d') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> {{ $purchaseOrder->status }}</p>
                    <p><strong>Expected Delivery:</strong> {{ $purchaseOrder->expected_delivery?->format('Y-m-d') ?? 'N/A' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('purchases.goods-receipts.store', $purchaseOrder) }}">
                @csrf
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Ordered</th>
                            <th>Already Received</th>
                            <th>Unit</th>
                            <th>Receive Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->items as $item)
                            <tr>
                                <td>{{ $item->rawMaterial->name }}</td>
                                <td>{{ number_format($item->quantity, 2) }}</td>
                                <td>{{ number_format($item->received_quantity, 2) }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>
                                    <input type="hidden" name="items[{{ $item->id }}][purchase_order_item_id]" value="{{ $item->id }}">
                                    <input type="number" name="items[{{ $item->id }}][received_quantity]" 
                                           class="form-control" 
                                           step="0.01" 
                                           min="0" 
                                           max="{{ $item->quantity - $item->received_quantity }}"
                                           placeholder="Max: {{ $item->quantity - $item->received_quantity }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3" placeholder="Any notes about this goods receipt..."></textarea>
                </div>

                <button type="submit" class="btn btn-success">Receive Goods</button>
                <a href="{{ route('purchases.purchase-orders.show', $purchaseOrder) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
