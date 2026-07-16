@extends('layouts.app')

@section('title', 'Purchase Order Details')
@section('page-title', 'Purchase Order Details')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ $purchaseOrder->purchase_number }}</h3>
            <div>
                @if ($purchaseOrder->status !== 'completed')
                    <a href="{{ route('purchases.goods-receipts.create', $purchaseOrder) }}" class="btn btn-success btn-sm">Receive Goods</a>
                    <a href="{{ route('purchases.payments.create', $purchaseOrder) }}" class="btn btn-primary btn-sm">Add Payment</a>
                    <a href="{{ route('purchases.purchase-orders.edit', $purchaseOrder) }}" class="btn btn-warning btn-sm">Edit</a>
                @endif
                <a href="{{ route('purchases.purchase-orders.index') }}" class="btn btn-secondary btn-sm">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->company_name }}</p>
                    <p><strong>Purchase Date:</strong> {{ $purchaseOrder->purchase_date->format('Y-m-d') }}</p>
                    <p><strong>Expected Delivery:</strong> {{ $purchaseOrder->expected_delivery ? $purchaseOrder->expected_delivery->format('Y-m-d') : 'N/A' }}</p>
                    <p><strong>Invoice Number:</strong> {{ $purchaseOrder->invoice_number ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> <span class="badge badge-{{ $purchaseOrder->status === 'completed' ? 'success' : ($purchaseOrder->status === 'partial' ? 'warning' : 'secondary') }}">{{ $purchaseOrder->status }}</span></p>
                    <p><strong>Payment Status:</strong> <span class="badge badge-{{ $purchaseOrder->payment_status === 'paid' ? 'success' : ($purchaseOrder->payment_status === 'partial' ? 'warning' : 'danger') }}">{{ $purchaseOrder->payment_status }}</span></p>
                    <p><strong>Created By:</strong> {{ $purchaseOrder->createdBy?->name ?? 'N/A' }}</p>
                </div>
            </div>

            <h4>Financial Summary</h4>
            <table class="table table-sm">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">{{ number_format($purchaseOrder->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td class="text-right">-{{ number_format($purchaseOrder->discount, 2) }}</td>
                </tr>
                <tr>
                    <td>Tax Amount</td>
                    <td class="text-right">{{ number_format($purchaseOrder->tax_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Transport Cost</td>
                    <td class="text-right">{{ number_format($purchaseOrder->transport_cost, 2) }}</td>
                </tr>
                <tr>
                    <td>Other Cost</td>
                    <td class="text-right">{{ number_format($purchaseOrder->other_cost, 2) }}</td>
                </tr>
                <tr class="font-weight-bold">
                    <td>Grand Total</td>
                    <td class="text-right">{{ number_format($purchaseOrder->grand_total, 2) }}</td>
                </tr>
                <tr>
                    <td>Paid Amount</td>
                    <td class="text-right">{{ number_format($purchaseOrder->paid_amount, 2) }}</td>
                </tr>
                <tr class="font-weight-bold">
                    <td>Remaining Amount</td>
                    <td class="text-right">{{ number_format($purchaseOrder->remaining_amount, 2) }}</td>
                </tr>
            </table>

            @if ($purchaseOrder->notes)
                <p><strong>Notes:</strong> {{ $purchaseOrder->notes }}</p>
            @endif

            <h4 class="mt-4">Items</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th>Received</th>
                        <th>Pending</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder->items as $item)
                        <tr>
                            <td>{{ $item->rawMaterial->name }}</td>
                            <td>{{ number_format($item->quantity, 2) }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->total, 2) }}</td>
                            <td>{{ number_format($item->received_quantity, 2) }}</td>
                            <td>{{ number_format($item->quantity - $item->received_quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 class="mt-4">Goods Receipts</h4>
            @if ($purchaseOrder->goodsReceipts->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Receipt Number</th>
                            <th>Received Date</th>
                            <th>Remarks</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->goodsReceipts as $receipt)
                            <tr>
                                <td>{{ $receipt->receipt_number }}</td>
                                <td>{{ $receipt->received_date->format('Y-m-d') }}</td>
                                <td>{{ $receipt->remarks ?? 'N/A' }}</td>
                                <td>{{ $receipt->createdBy?->name ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No goods receipts yet.</p>
            @endif

            <h4 class="mt-4">Payments</h4>
            @if ($purchaseOrder->payments->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                <td>{{ $payment->remarks ?? 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('purchases.payments.destroy', $payment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No payments yet.</p>
            @endif
        </div>
    </div>
@endsection
