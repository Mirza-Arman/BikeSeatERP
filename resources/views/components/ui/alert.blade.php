@props([
    'variant' => 'info',
    'dismissible' => false,
])

@php
$variants = [
    'info' => 'bg-blue-50 border-blue-500 text-blue-800',
    'success' => 'bg-emerald-50 border-emerald-500 text-emerald-800',
    'danger' => 'bg-red-50 border-red-500 text-red-800',
    'warning' => 'bg-orange-50 border-orange-500 text-orange-800',
];

$icons = [
    'info' => 'information-circle',
    'success' => 'check-circle',
    'danger' => 'exclamation-circle',
    'warning' => 'exclamation-triangle',
];

$variantClass = $variants[$variant] ?? $variants['info'];
$icon = $icons[$variant] ?? $icons['info'];
@endphp

<div class="border-l-4 rounded-lg p-4 {{ $variantClass }}" x-data="{ show: true }" x-show="show" x-transition>
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <x-heroicon-o-{{ $icon }} class="h-5 w-5" />
        </div>
        <div class="ml-3 flex-1">
            {{ $slot }}
        </div>
        @if ($dismissible)
            <div class="ml-auto pl-3">
                <button @click="show = false" class="inline-flex rounded-lg p-1.5 hover:bg-black/10 focus:outline-none focus:ring-2 focus:ring-offset-2">
                    <x-heroicon-o-x-mark class="h-5 w-5" />
                </button>
            </div>
        @endif
    </div>
</div>
