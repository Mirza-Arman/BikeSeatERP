@extends('layouts.modern')

@section('title', 'Edit Production Formula')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Production Formula</h1>
                <p class="text-gray-600 mt-2">{{ $formula->product->product_name }} - Version {{ $formula->version }}</p>
            </div>
            <a href="{{ route('erp.production.formula.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
                Back
            </a>
        </div>
    </div>

    <x-ui.card>
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Formula Details</h3>
            <x-heroicon-o-beaker class="h-5 w-5 text-gray-400" />
        </div>
        
        <form method="POST" action="{{ route('erp.production.formula.update', $formula) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
                    <select name="product_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                        <option value="">Select product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ $formula->product_id == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Version *</label>
                    <input type="text" name="version" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ $formula->version }}" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" rows="3">{{ $formula->description ?? '' }}</textarea>
                </div>
            </div>
                
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Formula Items (Raw Materials)</h3>
                <div id="items-container" class="space-y-4">
                    @foreach ($formula->items as $index => $item)
                        <div class="item-row grid grid-cols-12 gap-4 items-end">
                            <div class="col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Raw Material *</label>
                                <select name="items[{{ $index }}][raw_material_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                                    <option value="">Select material</option>
                                    @foreach ($rawMaterials as $material)
                                        <option value="{{ $material->id }}" {{ $item->raw_material_id == $material->id ? 'selected' : '' }}>{{ $material->name }} ({{ $material->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity Required *</label>
                                <input type="number" step="0.01" name="items[{{ $index }}][quantity_required]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ $item->quantity_required }}" required>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <input type="text" name="items[{{ $index }}][unit]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" value="{{ $item->unit }}">
                            </div>
                            <div class="col-span-2">
                                <button type="button" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 remove-item">
                                    <x-heroicon-o-trash class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200" id="add-item">
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Add Material
                </button>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200">
                    <x-heroicon-o-check class="h-5 w-5" />
                    Update Formula
                </button>
                <a href="{{ route('erp.production.formula.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </x-ui.card>
    
    <script>
        let itemCount = {{ $formula->items->count() }};
        
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'item-row grid grid-cols-12 gap-4 items-end';
            newRow.innerHTML = `
                <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Raw Material *</label>
                    <select name="items[${itemCount}][raw_material_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                        <option value="">Select material</option>
                        @foreach ($rawMaterials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity Required *</label>
                    <input type="number" step="0.01" name="items[${itemCount}][quantity_required]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none" required>
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                    <input type="text" name="items[${itemCount}][unit]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                </div>
                <div class="col-span-2">
                    <button type="button" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 remove-item">
                        <x-heroicon-o-trash class="h-5 w-5" />
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            itemCount++;
        });
        
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
                e.target.closest('.item-row').remove();
            }
        });
    </script>
@endsection
