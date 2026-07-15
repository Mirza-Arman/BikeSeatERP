@php
    $menuItems = config('erp.menu', []);
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4 erp-sidebar">
    <a href="{{ route('dashboard') }}" class="brand-link text-center">
        <span class="brand-text font-weight-light erp-brand-text">{{ config('erp.name') }}</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                @foreach ($menuItems as $item)
                    @if (isset($item['children']))
                        @php
                            $isOpen = isset($item['active']) && request()->routeIs($item['active']);
                        @endphp
                        <li class="nav-item {{ $isOpen ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ $isOpen ? 'active' : '' }}">
                                <i class="nav-icon {{ $item['icon'] }}"></i>
                                <p>
                                    {{ $item['title'] }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach ($item['children'] as $child)
                                    <li class="nav-item">
                                        <a href="{{ route($child['route']) }}"
                                           class="nav-link {{ request()->routeIs($child['route']) ? 'active' : '' }}">
                                            <i class="nav-icon {{ $child['icon'] ?? 'far fa-circle' }}"></i>
                                            <p>{{ $child['title'] }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route($item['route']) }}"
                               class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                <i class="nav-icon {{ $item['icon'] }}"></i>
                                <p>{{ $item['title'] }}</p>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
