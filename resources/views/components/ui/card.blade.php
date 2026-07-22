@props([
    'padding' => 'default',
    'border' => false,
    'hover' => false,
])

@php
$paddings = [
    'none' => '',
    'sm' => 'p-4',
    'default' => 'p-6',
    'lg' => 'p-8',
];

$paddingClass = $paddings[$padding] ?? $paddings['default'];
$borderClass = $border ? 'border border-gray-200' : '';
$hoverClass = $hover ? 'hover:shadow-xl hover:-translate-y-1 transition-all duration-300' : '';
@endphp

<div class="bg-white rounded-xl shadow-md {{ $paddingClass }} {{ $borderClass }} {{ $hoverClass }}">
    {{ $slot }}
</div>
