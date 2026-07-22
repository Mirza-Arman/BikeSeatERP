@extends('layouts.modern')

@section('title', 'Create Raw Material')

@section('content')
    <x-ui.page-header 
        title="Create Raw Material" 
        subtitle="Add a new material to your inventory"
        :actions="[
            '<a href=\"' . route('erp.raw-materials.index') . '\" class=\"inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors\"><x-heroicon-arrow-left class=\"h-5 w-5\" />Back</a>'
        ]"
    />

    <div class="max-w-4xl">
        <x-ui-card>
            <form method="POST" action="{{ route('erp.raw-materials.store') }}">
                @csrf
                
                {{-- Basic Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-cube class="h-5 w-5 text-blue-600" />
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select 
                                name="category_id" 
                                id="category-select" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                            >
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" data-attributes='@json($category->attributes)'>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                            <select 
                                name="supplier_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                            >
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Material Name *</label>
                            <input 
                                type="text" 
                                name="name" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="Enter material name"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit *</label>
                            <input 
                                type="text" 
                                name="unit" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="e.g., KG, PCS, MTR"
                            >
                        </div>
                    </div>
                </div>

                {{-- Dynamic Attributes --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-tag class="h-5 w-5 text-blue-600" />
                        Attributes
                    </h3>
                    <div id="attributes-container" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <p class="text-sm text-gray-500 col-span-3">Select a category to see available attributes</p>
                    </div>
                </div>

                {{-- Stock Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-o-chart-bar class="h-5 w-5 text-blue-600" />
                        Stock Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Stock</label>
                            <input 
                                type="number" 
                                name="minimum_stock" 
                                step="0.01" 
                                value="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="Minimum stock level"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock</label>
                            <input 
                                type="number" 
                                name="current_stock" 
                                step="0.01" 
                                value="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="Current stock level"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Price</label>
                            <input 
                                type="number" 
                                name="purchase_price" 
                                step="0.01" 
                                value="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="Purchase price per unit"
                            >
                        </div>
                    </div>
                </div>

                {{-- Additional Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-document-text class="h-5 w-5 text-blue-600" />
                        Additional Information
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            name="description" 
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none resize-none"
                            placeholder="Material description"
                        ></textarea>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('erp.raw-materials.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <x-heroicon-check class="h-5 w-5" />
                        Save Material
                    </button>
                </div>
            </form>
        </x-ui-card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category-select');
            const attributesContainer = document.getElementById('attributes-container');

            categorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const attributes = selectedOption.dataset.attributes ? JSON.parse(selectedOption.dataset.attributes) : [];

                attributesContainer.innerHTML = '';

                if (attributes.length === 0) {
                    attributesContainer.innerHTML = '<p class="text-sm text-gray-500 col-span-3">No attributes defined for this category</p>';
                    return;
                }

                attributes.forEach(attr => {
                    const div = document.createElement('div');
                    div.innerHTML = `
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ${attr.attribute_name} ${attr.is_required ? '*' : ''}
                        </label>
                        <input 
                            type="text" 
                            name="attributes[${attr.attribute_name}]" 
                            ${attr.is_required ? 'required' : ''}
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                            placeholder="Enter ${attr.attribute_name}"
                        >
                    `;
                    attributesContainer.appendChild(div);
                });
            });
        });
    </script>
@endsection
