@props([
    'title' => null,
    'subtitle' => null,
    'actions' => [],
])

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $title }}</h1>
            @if ($subtitle)
                <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
            @endif
        </div>
        
        @if (count($actions) > 0)
            <div class="flex items-center gap-3">
                @foreach ($actions as $action)
                    {{ $action }}
                @endforeach
            </div>
        @endif
    </div>
</div>
