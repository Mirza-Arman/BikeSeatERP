@extends('layouts.modern')

@section('title', 'Production Orders')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Production Orders</h1>
                <p class="text-gray-600 mt-2">Manage production orders and track manufacturing progress</p>
            </div>
            <a href="{{ route('erp.production.orders.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:shadow-md active:scale-95">
                <x-heroicon-o-plus class="h-5 w-5" />
                New Order
            </a>
        </div>
    </div>

    <x-ui-card>
        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($productionOrders as $productionOrder)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $productionOrder->order_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $productionOrder->product?->product_name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ number_format($productionOrder->quantity_to_produce, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($productionOrder->status === 'completed')
                                    <x-ui-badge variant="success">Completed</x-ui-badge>
                                @elseif ($productionOrder->status === 'in_progress')
                                    <x-ui-badge variant="warning">In Progress</x-ui-badge>
                                @else
                                    <x-ui-badge variant="secondary">Pending</x-ui-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('erp.production.orders.show', $productionOrder) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                        <x-heroicon-o-eye class="h-5 w-5" />
                                    </a>
                                    @if ($productionOrder->status !== 'completed')
                                        <form action="{{ route('erp.production.orders.status', $productionOrder) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Complete">
                                                <x-heroicon-o-check-circle class="h-5 w-5" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-o-cog class="h-12 w-12 text-gray-400 mb-3" />
                                    <p class="text-gray-500">No production orders found</p>
                                    <a href="{{ route('erp.production.orders.create') }}" class="mt-4 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                                        <x-heroicon-o-plus class="h-5 w-5" />
                                        Create your first production order
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($productionOrders->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Showing {{ $productionOrders->firstItem() }} to {{ $productionOrders->lastItem() }} of {{ $productionOrders->total() }} results
                </p>
                {{ $productionOrders->links('pagination::tailwind') }}
            </div>
        @endif
    </x-ui-card>
@endsection
