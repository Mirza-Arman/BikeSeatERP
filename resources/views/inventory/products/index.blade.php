@extends('layouts.modern')

@section('title', 'Products')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Products</h1>
                <p class="text-gray-600 mt-2">Manage your product catalog and inventory</p>
            </div>
            <a href="{{ route('erp.inventory.products.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:shadow-md active:scale-95">
                <x-heroicon-o-plus class="h-5 w-5" />
                New Product
            </a>
        </div>
    </div>

    <x-ui-card>
        {{-- Filters --}}
        <form method="GET" class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1 relative">
                <x-heroicon-o-magnifying-glass class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="primary" icon="magnifying-glass">Search</x-ui.button>
                @if (request('search'))
                    <a href="{{ route('erp.inventory.products.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->product_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->product_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ number_format($product->current_stock, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PKR {{ number_format($product->selling_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($product->status === 'active')
                                    <x-ui-badge variant="success">Active</x-ui-badge>
                                @else
                                    <x-ui-badge variant="secondary">Inactive</x-ui-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('erp.inventory.products.show', $product) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                        <x-heroicon-o-eye class="h-5 w-5" />
                                    </a>
                                    <a href="{{ route('erp.inventory.products.edit', $product) }}" class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                        <x-heroicon-o-pencil class="h-5 w-5" />
                                    </a>
                                    <form action="{{ route('erp.inventory.products.toggle-status', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 {{ $product->status === 'active' ? 'text-gray-600 hover:bg-gray-50' : 'text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition-colors" title="{{ $product->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            <x-heroicon-o-power class="h-5 w-5" />
                                        </button>
                                    </form>
                                    <button onclick="confirmDelete('{{ route('erp.inventory.products.destroy', $product) }}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <x-heroicon-o-trash class="h-5 w-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-o-cube class="h-12 w-12 text-gray-400 mb-3" />
                                    <p class="text-gray-500">No products found</p>
                                    <a href="{{ route('erp.inventory.products.create') }}" class="mt-4 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                                        <x-heroicon-o-plus class="h-5 w-5" />
                                        Add your first product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                </p>
                {{ $products->links('pagination::tailwind') }}
            </div>
        @endif
    </x-ui-card>

    {{-- Delete Confirmation Modal --}}
    <x-ui.modal id="deleteModal" title="Delete Product">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <p class="text-gray-600 mb-6">Are you sure you want to delete this product? This action cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('deleteModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </div>
        </form>
    </x-ui.modal>

    <script>
        function confirmDelete(url) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
@endsection
