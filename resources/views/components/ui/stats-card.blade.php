@props([
    'title',
    'value',
    'icon',
    'trend' => null,
    'trendValue' => null,
    'color' => 'blue',
])

@php
$colors = [
    'blue' => 'border-blue-500 bg-blue-50 text-blue-600',
    'emerald' => 'border-emerald-500 bg-emerald-50 text-emerald-600',
    'orange' => 'border-orange-500 bg-orange-50 text-orange-600',
    'red' => 'border-red-500 bg-red-50 text-red-600',
    'purple' => 'border-purple-500 bg-purple-50 text-purple-600',
    'cyan' => 'border-cyan-500 bg-cyan-50 text-cyan-600',
];

$colorClass = $colors[$color] ?? $colors['blue'];
$trendColor = $trend === 'up' ? 'text-emerald-600' : ($trend === 'down' ? 'text-red-600' : 'text-gray-600');
$trendIcon = $trend === 'up' ? 'trending-up' : ($trend === 'down' ? 'trending-down' : 'minus');
@endphp

<div class="bg-white rounded-xl shadow-md p-6 border-l-4 {{ $colorClass }} hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $value }}</p>
            @if ($trend && $trendValue)
                <p class="text-sm {{ $trendColor }} mt-2 flex items-center gap-1">
                    <x-heroicon-{{ $trendIcon }} class="h-4 w-4" />
                    {{ $trendValue }}
                </p>
            @endif
        </div>
        <div class="p-3 rounded-lg {{ $colorClass }}">
            <x-heroicon-{{ $icon }} class="h-8 w-8" />
        </div>
    </div>
</div>
