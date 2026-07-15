<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Home</a>
    </li>

    @if (isset($breadcrumbs) && is_array($breadcrumbs))
        @foreach ($breadcrumbs as $crumb)
            @if ($loop->last)
                <li class="breadcrumb-item active">{{ $crumb['label'] }}</li>
            @else
                <li class="breadcrumb-item">
                    @if (isset($crumb['url']))
                        <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                    @else
                        {{ $crumb['label'] }}
                    @endif
                </li>
            @endif
        @endforeach
    @else
        @yield('breadcrumb')
    @endif
</ol>
