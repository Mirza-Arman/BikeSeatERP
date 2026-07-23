@extends('layouts.modern')

@section('title', 'Supplier Ledger')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Supplier Ledger</h1>
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
            <h3 class="text-lg font-semibold text-gray-900">Ledger Transactions</h3>
            <x-heroicon-o-book class="h-5 w-5 text-gray-400" />
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Debit</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Credit</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($ledger as $entry)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $entry['date'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry['reference_number'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $entry['material_purchased'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600">{{ $entry['debit'] > 0 ? number_format($entry['debit'], 2) : '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-emerald-600">{{ $entry['credit'] > 0 ? number_format($entry['credit'], 2) : '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">{{ number_format($entry['balance'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $entry['remarks'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
