@extends('layouts.modern')

@section('title', 'Production Formula')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Production Formulas</h1>
                <p class="text-gray-600 mt-2">Manage production formulas and raw material requirements</p>
            </div>
            <a href="{{ route('erp.production.formula.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-plus class="h-5 w-5" />
                New Formula
            </a>
        </div>
    </div>

    <x-ui.card>
        {{-- Search --}}
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1 relative">
                <x-heroicon-o-magnifying-glass class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by product" class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <x-heroicon-o-funnel class="h-5 w-5" />
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Version</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Items Count</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($formulas as $formula)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $formula->product->product_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $formula->version }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $formula->items->count() }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $formula->description ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('erp.production.formula.show', $formula) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                        View
                                    </a>
                                    <a href="{{ route('erp.production.formula.edit', $formula) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                        Edit
                                    </a>
                                    <form action="{{ route('erp.production.formula.destroy', $formula) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200" type="submit" onclick="return confirm('Delete this formula?')">
                                            <x-heroicon-o-trash class="h-4 w-4" />
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-o-beaker class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                                    <p class="text-gray-500">No formulas found</p>
                                    <a href="{{ route('erp.production.formula.create') }}" class="mt-2 text-blue-600 hover:text-blue-700">Create your first formula</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($formulas->hasPages())
            <div class="mt-6">
                {{ $formulas->links() }}
            </div>
        @endif
    </x-ui.card>
@endsection
