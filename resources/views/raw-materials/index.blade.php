@extends('layouts.modern')

@section('title', 'Raw Materials')

@section('content')
    <x-ui.page-header 
        title="Raw Materials" 
        subtitle="Manage your material inventory and stock levels"
        :actions="[
            '<a href=\"' . route('erp.raw-materials.create') . '\" class=\"inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors\"><x-heroicon-plus class=\"h-5 w-5\" />New Material</a>'
        ]"
    />

    {{-- Stock Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-folder class="h-5 w-5 text-blue-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Categories</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stockSummary['total_categories'] }}</p>
                </div>
            </div>
        </x-ui-card>
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-cube class="h-5 w-5 text-emerald-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Materials</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stockSummary['total_materials'] }}</p>
                </div>
            </div>
        </x-ui-card>
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-exclamation-triangle class="h-5 w-5 text-orange-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Low Stock</p>
                    <p class="text-xl font-bold text-orange-600">{{ $stockSummary['low_stock_items'] }}</p>
                </div>
            </div>
        </x-ui-card>
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-x-circle class="h-5 w-5 text-red-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Out of Stock</p>
                    <p class="text-xl font-bold text-red-600">{{ $stockSummary['out_of_stock_items'] }}</p>
                </div>
            </div>
        </x-ui-card>
        <x-ui-card padding="sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-currency-dollar class="h-5 w-5 text-purple-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">Inventory Value</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($stockSummary['inventory_value'], 2) }}</p>
                </div>
            </div>
        </x-ui-card>
    </div>

    <x-ui-card>
        {{-- Filters --}}
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1 relative">
                <x-heroicon-magnifying-glass class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search materials..." 
                    class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                >
            </div>
            <div class="flex gap-2 flex-wrap">
                <select name="category_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="supplier_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <option value="">All Suppliers</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
                    @endforeach
                </select>
                <select name="stock_status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <option value="">All Stock Status</option>
                    <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <x-heroicon-arrow-path class="h-5 w-5" />
                </button>
                <a href="{{ route('erp.raw-materials.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">
                    Clear
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Attributes</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Avg Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($materials as $material)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $material->material_code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $material->name }}</div>
                            <div class="text-sm text-gray-500">{{ $material->unit }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $material->category?->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $material->supplier?->company_name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if (is_array($material->attributes) && count($material->attributes) > 0)
                                    <div class="text-xs text-gray-600">
                                        @foreach ($material->attributes as $key => $value)
                                            @if ($value){{ ucfirst($key) }}: {{ $value }}<br>@endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-ui-badge :variant="$material->current_stock <= $material->minimum_stock ? 'warning' : 'success'">
                                    {{ number_format($material->current_stock, 2) }} {{ $material->unit }}
                                </x-ui-badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($material->average_cost, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-ui-badge :variant="$material->status === 'active' ? 'success' : 'secondary'">
                                    {{ ucfirst($material->status) }}
                                </x-ui-badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('erp.raw-materials.show', $material) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                        <x-heroicon-eye class="h-5 w-5" />
                                    </a>
                                    <a href="{{ route('erp.raw-materials.edit', $material) }}" class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                        <x-heroicon-pencil class="h-5 w-5" />
                                    </a>
                                    <form action="{{ route('erp.raw-materials.destroy', $material) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this material?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <x-heroicon-trash class="h-5 w-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-cube class="h-12 w-12 text-gray-400 mb-3" />
                                    <p class="text-gray-500">No materials found</p>
                                    <a href="{{ route('erp.raw-materials.create') }}" class="mt-2 text-blue-600 hover:text-blue-700">Add your first material</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($materials->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Showing {{ $materials->firstItem() }} to {{ $materials->lastItem() }} of {{ $materials->total() }} results
                </div>
                {{ $materials->links('pagination::tailwind') }}
            </div>
        @endif
    </x-ui-card>
@endsection
