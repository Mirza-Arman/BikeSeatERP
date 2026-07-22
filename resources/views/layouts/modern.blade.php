<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('erp.name')) - {{ config('erp.name') }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 h-full" x-data="{ sidebarOpen: false }">
    <div class="flex h-full overflow-hidden">
        
        {{-- Sidebar --}}
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex flex-col h-full">
                {{-- Logo --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">BS</span>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900">BikeSeat ERP</h1>
                            <p class="text-xs text-gray-500">Motorcycle Seat Factory</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <x-heroicon-x-mark class="h-6 w-6" />
                    </button>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-home class="h-5 w-5" />
                        Dashboard
                    </a>

                    <a href="{{ route('erp.suppliers.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('erp/suppliers*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-truck class="h-5 w-5" />
                        Suppliers
                    </a>

                    <a href="{{ route('erp.raw-materials.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('erp/raw-materials*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-cube class="h-5 w-5" />
                        Raw Materials
                    </a>

                    <a href="{{ route('purchases.purchase-orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('purchases*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-shopping-cart class="h-5 w-5" />
                        Purchase Management
                    </a>

                    <a href="{{ route('erp.production.orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('erp/production*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-cog class="h-5 w-5" />
                        Production
                    </a>

                    <a href="{{ route('erp.inventory.stock.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('erp/inventory*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-archive-box class="h-5 w-5" />
                        Inventory
                    </a>

                    <a href="{{ route('erp.customers.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('erp/customers*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-users class="h-5 w-5" />
                        Customers
                    </a>

                    <a href="{{ route('erp.reports.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('erp/reports*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-chart-bar class="h-5 w-5" />
                        Reports
                    </a>

                    <a href="{{ route('erp.settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('erp/settings*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <x-heroicon-cog-6-tooth class="h-5 w-5" />
                        Settings
                    </a>
                </nav>

                {{-- User Profile --}}
                <div class="px-4 py-4 border-t border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                            <span class="text-primary-700 font-semibold">{{ auth()->user()->name[0] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors">
                                <x-heroicon-arrow-right-on-rectangle class="h-5 w-5" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            
            {{-- Top Navbar --}}
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                            <x-heroicon-bars-3 class="h-6 w-6" />
                        </button>
                        
                        {{-- Search --}}
                        <div class="hidden md:block relative">
                            <x-heroicon-magnifying-glass class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                            <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 w-64 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        {{-- Date --}}
                        <div class="hidden md:flex items-center text-sm text-gray-600">
                            <x-heroicon-calendar class="h-5 w-5 mr-2" />
                            {{ now()->format('M d, Y') }}
                        </div>

                        {{-- Notifications --}}
                        <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <x-heroicon-bell class="h-6 w-6" />
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        {{-- Dark Mode Toggle --}}
                        <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <x-heroicon-moon class="h-6 w-6" />
                        </button>

                        {{-- User Avatar --}}
                        <div class="relative">
                            <button class="flex items-center gap-2 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-primary-700 font-semibold text-sm">{{ auth()->user()->name[0] }}</span>
                                </div>
                                <x-heroicon-chevron-down class="h-4 w-4 text-gray-500" />
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @include('layouts.partials.flash-messages')
                
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Overlay for mobile sidebar --}}
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
