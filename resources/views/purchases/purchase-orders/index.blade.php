@extends('layouts.modern')

@section('title', 'Purchase Orders')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Purchase Orders</h1>
                <p class="text-gray-600 mt-2">Manage purchase orders and supplier payments</p>
            </div>
            <a href="{{ route('purchases.purchase-orders.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:shadow-md active:scale-95">
                <x-heroicon-plus class="h-5 w-5" />
                New Purchase Order
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-calendar class="h-5 w-5 text-blue-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Today's Purchases</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($statistics['today_purchases'], 2) }}</p>
                </div>
            </div>
        </x-ui-card>
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-chart-bar class="h-5 w-5 text-emerald-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Monthly Purchases</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($statistics['monthly_purchases'], 2) }}</p>
                </div>
            </div>
        </x-ui-card>
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-clock class="h-5 w-5 text-orange-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Pending Orders</p>
                    <p class="text-lg font-bold text-orange-600">{{ $statistics['pending_orders'] }}</p>
                </div>
            </div>
        </x-ui-card>
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-check-circle class="h-5 w-5 text-green-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Completed Orders</p>
                    <p class="text-lg font-bold text-green-600">{{ $statistics['completed_orders'] }}</p>
                </div>
            </div>
        </x-ui-card>
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-currency-dollar class="h-5 w-5 text-red-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Outstanding</p>
                    <p class="text-lg font-bold text-red-600">{{ number_format($statistics['supplier_outstanding'], 2) }}</p>
                </div>
            </div>
        </x-ui-card>
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-exclamation-triangle class="h-5 w-5 text-gray-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Overdue</p>
                    <p class="text-lg font-bold text-gray-600">{{ $statistics['overdue_orders'] }}</p>
                </div>
            </div>
        </x-ui-card>
    </div>

    <x-ui-card>
        {{-- Filters --}}
        <form method="GET" class="flex flex-col lg:flex-row gap-4 mb-6">
            <div class="flex-1 relative">
                <x-heroicon-magnifying-glass class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search PO number, invoice, supplier..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="flex flex-wrap gap-2">
                <select name="supplier_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <option value="">All Suppliers</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <select name="payment_status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <option value="">All Payment Status</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <x-heroicon-funnel class="h-5 w-5" />
                </button>
                <a href="{{ route('purchases.purchase-orders.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">
                    Clear
                </a>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">PO Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($purchaseOrders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->purchase_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->purchase_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->supplier->company_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PKR {{ number_format($order->grand_total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($order->status === 'completed')
                                    <x-ui-badge variant="success">Completed</x-ui-badge>
                                @elseif ($order->status === 'partial')
                                    <x-ui-badge variant="warning">Partial</x-ui-badge>
                                @else
                                    <x-ui-badge variant="secondary">Pending</x-ui-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($order->payment_status === 'paid')
                                    <x-ui-badge variant="success">Paid</x-ui-badge>
                                @elseif ($order->payment_status === 'partial')
                                    <x-ui-badge variant="warning">Partial</x-ui-badge>
                                @else
                                    <x-ui-badge variant="danger">Unpaid</x-ui-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('purchases.purchase-orders.show', $order) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                        <x-heroicon-eye class="h-5 w-5" />
                                    </a>
                                    @if ($order->status !== 'completed')
                                        <a href="{{ route('purchases.purchase-orders.edit', $order) }}" class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                            <x-heroicon-pencil class="h-5 w-5" />
                                        </a>
                                    @endif
                                    <button onclick="confirmDelete('{{ route('purchases.purchase-orders.destroy', $order) }}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <x-heroicon-trash class="h-5 w-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-shopping-cart class="h-12 w-12 text-gray-400 mb-3" />
                                    <p class="text-gray-500">No purchase orders found</p>
                                    <a href="{{ route('purchases.purchase-orders.create') }}" class="mt-4 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                                        <x-heroicon-plus class="h-5 w-5" />
                                        Create your first purchase order
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($purchaseOrders->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Showing {{ $purchaseOrders->firstItem() }} to {{ $purchaseOrders->lastItem() }} of {{ $purchaseOrders->total() }} results
                </p>
                {{ $purchaseOrders->links('pagination::tailwind') }}
            </div>
        @endif
    </x-ui-card>

    {{-- Delete Confirmation Modal --}}
    <x-ui.modal id="deleteModal" title="Delete Purchase Order">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <p class="text-gray-600 mb-6">Are you sure you want to delete this purchase order? This action cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('deleteModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </div>
        </form>
    </x-ui.modal>

    <script>
        function confirmDelete(url) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
@endsection
