@extends('layouts.modern')

@section('title', 'Materials Supplied')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Materials Supplied</h1>
                <p class="text-gray-600 mt-2">{{ $supplier->company_name }}</p>
            </div>
            <a href="{{ route('erp.suppliers.show', $supplier) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
                Back to Supplier
            </a>
        </div>
    </div>

    <x-ui.card>
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Materials Supplied</h3>
            <x-heroicon-o-cube class="h-5 w-5 text-gray-400" />
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Model</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quality</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Purchase</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($materials as $material)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $material['material'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $material['model'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $material['quality'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $material['unit'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($material['last_price'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $material['last_purchase'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                                <p class="text-gray-500">No materials supplied yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
