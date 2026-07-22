@extends('layouts.modern')

@section('title', 'Edit Product')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('erp.inventory.products.index') }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <x-heroicon-arrow-left class="h-6 w-6" />
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Product</h1>
                <p class="text-gray-600 mt-2">Update product information</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl">
        <x-ui-card>
            <form method="POST" action="{{ route('erp.inventory.products.update', $product) }}">
                @csrf
                @method('PUT')
                
                {{-- Basic Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-cube class="h-5 w-5 text-blue-600" />
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Code <span class="text-red-500">*</span></label>
                            <input type="text" name="product_code" value="{{ old('product_code', $product->product_code) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="PRD-001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" name="product_name" value="{{ old('product_name', $product->product_name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="Enter product name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                            <input type="text" name="model" value="{{ old('model', $product->model) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="Model number">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selling Price</label>
                            <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="0.00">
                        </div>
                    </div>
                </div>

                {{-- Stock Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-o-chart-bar class="h-5 w-5 text-blue-600" />
                        Stock Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Stock</label>
                            <input type="number" step="0.01" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock</label>
                            <input type="number" step="0.01" name="current_stock" value="{{ old('current_stock', $product->current_stock) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" placeholder="0.00">
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('erp.inventory.products.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <x-heroicon-x-mark class="h-5 w-5" />
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <x-heroicon-check class="h-5 w-5" />
                        Update Product
                    </button>
                </div>
            </form>
        </x-ui-card>
    </div>
@endsection
