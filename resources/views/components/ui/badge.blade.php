@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
$variants = [
    'default' => 'bg-gray-100 text-gray-800',
    'primary' => 'bg-blue-100 text-blue-800',
    'success' => 'bg-emerald-100 text-emerald-800',
    'danger' => 'bg-red-100 text-red-800',
    'warning' => 'bg-orange-100 text-orange-800',
    'info' => 'bg-cyan-100 text-cyan-800',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-1 text-sm',
    'lg' => 'px-3 py-1.5 text-base',
];

$variantClass = $variants[$variant] ?? $variants['default'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<span class="inline-flex items-center font-medium rounded-full {{ $variantClass }} {{ $sizeClass }}">
    {{ $slot }}
</span>
