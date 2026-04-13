<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100 dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="min-h-screen flex">

        {{-- Sidebar --}}
        <aside class="w-64 bg-gray-900 text-white flex flex-col shrink-0">
            <div class="px-6 py-5 border-b border-gray-700">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-white">
                    🌾 cFarm Admin
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard',   'icon' => '📊', 'label' => 'Dashboard'],
                        ['route' => 'admin.users',        'icon' => '👥', 'label' => 'Users'],
                        ['route' => 'admin.listings',     'icon' => '📋', 'label' => 'Listings'],
                        ['route' => 'admin.commodities',  'icon' => '🌽', 'label' => 'Commodities'],
                        ['route' => 'admin.scraper',      'icon' => '🤖', 'label' => 'KAMIS Scraper'],
                        ['route' => 'admin.notifications','icon' => '🔔', 'label' => 'Price Alerts'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                               {{ request()->routeIs($item['route'])
                                    ? 'bg-indigo-600 text-white'
                                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <span>{{ $item['icon'] }}</span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-4 py-4 border-t border-gray-700">
                <p class="text-xs text-gray-400 mb-2">{{ auth()->user()->name }}</p>
                <a href="{{ route('dashboard') }}"
                    class="text-xs text-indigo-400 hover:text-indigo-300">
                    ← Back to App
                </a>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white dark:bg-gray-800 shadow-sm px-8 py-4 flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    {{ $header ?? 'Admin' }}
                </h1>
                <span class="text-xs bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300
                             px-2 py-1 rounded-full font-medium">
                    Admin Mode
                </span>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 text-green-700
                                dark:text-green-300 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 text-red-700
                                dark:text-red-300 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
