@props([
    'items' => [],
])

<nav class="flex" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2">
        @foreach ($items as $index => $item)
            <li class="flex items-center">
                @if ($index === 0)
                    <a href="{{ $item['url'] }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <x-heroicon-home class="h-5 w-5" />
                    </a>
                @elseif ($index === count($items) - 1)
                    <span class="ml-2 text-gray-900 font-medium">{{ $item['title'] }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="ml-2 text-gray-500 hover:text-gray-700 transition-colors">
                        {{ $item['title'] }}
                    </a>
                @endif
                
                @if ($index < count($items) - 1)
                    <x-heroicon-chevron-right class="h-4 w-4 ml-2 text-gray-400" />
                @endif
            </li>
        @endforeach
    </ol>
</nav>
