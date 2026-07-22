@props([
    'id' => null,
    'title' => null,
    'maxWidth' => 'md',
])

@php
$maxWidths = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    'full' => 'max-w-full',
];

$maxWidthClass = $maxWidths[$maxWidth] ?? $maxWidths['md'];
@endphp

<div
    x-data="{ open: false }"
    @keydown.escape.window="open = false"
    class="relative z-50"
>
    <button @click="open = true" type="button">
        {{ $trigger }}
    </button>

    <div
        x-show="open"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        aria-hidden="true"
    ></div>

    <div
        x-show="open"
        class="fixed inset-0 z-10 overflow-y-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all {{ $maxWidthClass }}"
                @click.away="open = false"
            >
                @if ($title)
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    </div>
                @endif

                <div class="px-6 py-4">
                    {{ $slot }}
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                    <button @click="open = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    {{ $footer }}
                </div>
            </div>
        </div>
    </div>
</div>
