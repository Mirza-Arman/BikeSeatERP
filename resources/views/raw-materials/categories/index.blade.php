@extends('layouts.modern')

@section('title', 'Categories')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Material Categories</h1>
                <p class="text-gray-600 mt-2">Manage raw material categories</p>
            </div>
            <a href="{{ route('erp.raw-materials.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-plus class="h-5 w-5" />
                New Category
            </a>
        </div>
    </div>

    <x-ui.card>
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Categories</h3>
            <x-heroicon-o-tag class="h-5 w-5 text-gray-400" />
        </div>
        
        {{-- Search --}}
        <form method="GET" class="mb-6">
            <div class="flex gap-4">
                <input type="text" name="search" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ request('search') }}" placeholder="Search category...">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                    Search
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $category->description ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('erp.raw-materials.categories.edit', $category) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                        Edit
                                    </a>
                                    <form action="{{ route('erp.raw-materials.categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200" onclick="return confirm('Are you sure you want to delete this category?')">
                                            <x-heroicon-o-trash class="h-4 w-4" />
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                                <p class="text-gray-500">No categories found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($categories->hasPages())
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        @endif
    </x-ui.card>
@endsection
