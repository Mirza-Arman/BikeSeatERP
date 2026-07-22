@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'icon' => null,
    'error' => null,
])

@php
$id = $id ?? $name;
$errorClass = $error ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500';
$iconPadding = $icon ? 'pl-10' : '';
@endphp

<div class="relative">
    @if ($icon)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <x-heroicon-{{ $icon }} class="h-5 w-5 text-gray-400" />
        </div>
    @endif
    
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-offset-2 focus:outline-none transition-colors duration-200 {{ $errorClass }} {{ $iconPadding }}"
    >
    
    @if ($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
