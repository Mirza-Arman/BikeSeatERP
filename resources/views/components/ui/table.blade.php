@props([
    'headers' => [],
    'rows' => [],
    'empty' => 'No data available',
])

<div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                @foreach ($headers as $header)
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($rows as $row)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    {{ $row }}
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <x-heroicon-o-inbox class="h-12 w-12 text-gray-400 mb-3" />
                            <p class="text-gray-500">{{ $empty }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
