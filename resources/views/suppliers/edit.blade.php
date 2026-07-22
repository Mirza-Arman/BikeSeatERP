@extends('layouts.modern')

@section('title', 'Edit Supplier')

@section('content')
    <x-ui-page-header 
        title="Edit Supplier" 
        subtitle="Update supplier information"
        :actions="[
            '<a href=\"' . route('erp.suppliers.show', $supplier) . '\" class=\"inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors\"><x-heroicon-arrow-left class=\"h-5 w-5\" />Back</a>'
        ]"
    />

    <div class="max-w-4xl">
        <x-ui-card>
            <form method="POST" action="{{ route('erp.suppliers.update', $supplier) }}">
                @csrf
                @method('PUT')
                
                {{-- Basic Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-user class="h-5 w-5 text-blue-600" />
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                            <input 
                                type="text" 
                                name="company_name" 
                                value="{{ old('company_name', $supplier->company_name) }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="Enter company name"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Code</label>
                            <input 
                                type="text" 
                                name="supplier_code" 
                                value="{{ old('supplier_code', $supplier->supplier_code) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="Auto-generated if empty"
                            >
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-phone class="h-5 w-5 text-blue-600" />
                        Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                            <input 
                                type="text" 
                                name="contact_person" 
                                value="{{ old('contact_person', $supplier->contact_person) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="Primary contact name"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone', $supplier->phone) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="Phone number"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                            <input 
                                type="text" 
                                name="whatsapp" 
                                value="{{ old('whatsapp', $supplier->whatsapp) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="WhatsApp number"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email', $supplier->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="email@company.com"
                            >
                        </div>
                    </div>
                </div>

                {{-- Address Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-map-pin class="h-5 w-5 text-blue-600" />
                        Address Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea 
                                name="address" 
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none resize-none"
                                placeholder="Full street address"
                            >{{ old('address', $supplier->address) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input 
                                type="text" 
                                name="city" 
                                value="{{ old('city', $supplier->city) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="City name"
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea 
                            name="notes" 
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none resize-none"
                            placeholder="Any additional notes about this supplier"
                        >{{ old('notes', $supplier->notes) }}</textarea>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('erp.suppliers.show', $supplier) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <x-heroicon-check class="h-5 w-5" />
                        Update Supplier
                    </button>
                </div>
            </form>
        </x-ui-card>
    </div>
@endsection
