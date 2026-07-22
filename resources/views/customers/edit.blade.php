@extends('layouts.modern')

@section('title', 'Edit Customer')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('erp.customers.index') }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <x-heroicon-arrow-left class="h-6 w-6" />
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Customer</h1>
                <p class="text-gray-600 mt-2">Update customer information</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <x-ui.card>
        <form method="POST" action="{{ route('erp.customers.update', $customer) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Customer Code --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Code</label>
                    <div class="relative">
                        <x-heroicon-tag class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                        <input type="text" name="customer_code" value="{{ old('customer_code', $customer->customer_code) }}" placeholder="CUST-001" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    </div>
                    @error('customer_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Customer Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <x-heroicon-user class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                        <input type="text" name="customer_name" value="{{ old('customer_name', $customer->customer_name) }}" required placeholder="John Doe" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    </div>
                    @error('customer_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <div class="relative">
                        <x-heroicon-phone class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="+92 300 1234567" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    </div>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <x-heroicon-envelope class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" placeholder="customer@example.com" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- City --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <div class="relative">
                        <x-heroicon-map-pin class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                        <input type="text" name="city" value="{{ old('city', $customer->city) }}" placeholder="Lahore" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    </div>
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <div class="relative">
                        <x-heroicon-home class="h-5 w-5 absolute left-3 top-3 text-gray-400" />
                        <textarea name="address" rows="3" placeholder="123 Main Street, Lahore, Pakistan" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none resize-none">{{ old('address', $customer->address) }}</textarea>
                    </div>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="mt-8 flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('erp.customers.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <x-heroicon-x-mark class="h-5 w-5" />
                    Cancel
                </a>
                <x-ui.button type="submit" variant="primary" icon="check">Update Customer</x-ui.button>
            </div>
        </form>
    </x-ui.card>
@endsection
