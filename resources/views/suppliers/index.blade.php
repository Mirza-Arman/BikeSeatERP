@extends('layouts.modern')

@section('title', 'Suppliers')

@section('content')
    <x-ui-page-header 
        title="Suppliers" 
        subtitle="Manage your supplier directory and vendor relationships"
        :actions="[
            '<a href=\"' . route('erp.suppliers.create') . '\" class=\"inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors\"><x-heroicon-plus class=\"h-5 w-5\" />New Supplier</a>'
        ]"
    />

    <x-ui-card>
        {{-- Filters --}}
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1 relative">
                <x-heroicon-magnifying-glass class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search suppliers..." 
                    class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                >
            </div>
            <div class="flex gap-2">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <x-heroicon-arrow-path class="h-5 w-5" />
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">City</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $supplier->supplier_code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $supplier->company_name }}</div>
                                <div class="text-sm text-gray-500">{{ $supplier->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $supplier->contact_person }}</div>
                                <div class="text-sm text-gray-500">{{ $supplier->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $supplier->city ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $supplier->balance > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                {{ number_format($supplier->balance, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-ui-badge :variant="$supplier->status === 'active' ? 'success' : 'secondary'">
                                    {{ ucfirst($supplier->status) }}
                                </x-ui-badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('erp.suppliers.show', $supplier) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                        <x-heroicon-eye class="h-5 w-5" />
                                    </a>
                                    <a href="{{ route('erp.suppliers.edit', $supplier) }}" class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                        <x-heroicon-pencil class="h-5 w-5" />
                                    </a>
                                    <form action="{{ route('erp.suppliers.toggle-status', $supplier) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 {{ $supplier->status === 'active' ? 'text-gray-600 hover:bg-gray-50' : 'text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition-colors" title="{{ $supplier->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            @if ($supplier->status === 'active')
                                                <x-heroicon-x-circle class="h-5 w-5" />
                                            @else
                                                <x-heroicon-check-circle class="h-5 w-5" />
                                            @endif
                                        </button>
                                    </form>
                                    <form action="{{ route('erp.suppliers.destroy', $supplier) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-truck class="h-12 w-12 text-gray-400 mb-3" />
                                    <p class="text-gray-500">No suppliers found</p>
                                    <a href="{{ route('erp.suppliers.create') }}" class="mt-2 text-blue-600 hover:text-blue-700">Add your first supplier</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($suppliers->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }} of {{ $suppliers->total() }} results
                </div>
                {{ $suppliers->links('pagination::tailwind') }}
            </div>
        @endif
    </x-ui-card>
@endsection
