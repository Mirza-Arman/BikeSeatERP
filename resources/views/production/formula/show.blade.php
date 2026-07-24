@extends('layouts.modern')

@section('title', 'Production Formula Details')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Production Formula Details</h1>
                <p class="text-gray-600 mt-2">{{ $formula->product->product_name }} - Version {{ $formula->version }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('erp.production.formula.edit', $formula) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-pencil class="h-5 w-5" />
                    Edit
                </a>
                <a href="{{ route('erp.production.formula.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-arrow-left class="h-5 w-5" />
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Formula Information --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Formula Information</h3>
                <x-heroicon-o-information-circle class="h-5 w-5 text-gray-400" />
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Product</span>
                    <span class="text-sm font-medium text-gray-900">{{ $formula->product->product_name }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Version</span>
                    <span class="text-sm font-medium text-gray-900">{{ $formula->version }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Total Items</span>
                    <span class="text-sm font-medium text-gray-900">{{ $formula->items->count() }}</span>
                </div>
                <div class="py-2">
                    <span class="text-sm text-gray-600 block mb-1">Description</span>
                    <span class="text-sm text-gray-900">{{ $formula->description ?? '—' }}</span>
                </div>
            </div>
        </x-ui.card>

        {{-- Formula Items --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Formula Items</h3>
                <x-heroicon-o-list class="h-5 w-5 text-gray-400" />
            </div>
            @if ($formula->items->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Raw Material</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($formula->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->rawMaterial->name }}</td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-600">{{ number_format($item->quantity_required, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->unit ?? $item->rawMaterial->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-gray-500">No formula items found</p>
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
