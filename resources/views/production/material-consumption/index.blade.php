@extends('layouts.modern')

@section('title', 'Material Consumption')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Material Consumption</h1>
                <p class="text-gray-600 mt-2">Track material usage in production</p>
            </div>
            <a href="{{ route('erp.production.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
                Back to Orders
            </a>
        </div>
    </div>

    <x-ui.card>
        {{-- Filters --}}
        <form method="GET" class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1 relative">
                <x-heroicon-o-magnifying-glass class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by material or order..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                    Search
                </button>
                @if (request('search'))
                    <a href="{{ route('erp.production.material-consumption.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <x-heroicon-o-arrow-path class="h-5 w-5" />
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Production Order</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity Used</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($consumptions ?? [] as $consumption)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $consumption->consumption_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $consumption->rawMaterial?->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $consumption->productionOrder?->order_number ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($consumption->quantity, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $consumption->unit ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $consumption->notes ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-o-fire class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                                    <p class="text-gray-500">No material consumption records found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (isset($consumptions) && $consumptions->hasPages())
            <div class="mt-6">
                {{ $consumptions->links() }}
            </div>
        @endif
    </x-ui.card>
@endsection
