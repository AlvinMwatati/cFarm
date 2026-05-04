<!DOCTYPE html>
<html lang="en" class="h-full bg-parchment">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'cFarm Marketplace') }} — Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            pointer-events: none;
            z-index: 50;
            opacity: 0.35;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-soil relative">
    <div class="min-h-screen flex relative z-10">

        {{-- Sidebar --}}
        <aside class="w-64 bg-soil text-white flex flex-col shrink-0 shadow-xl z-20 relative">
            <div class="px-6 py-6 border-b border-bark/30">
                <a href="{{ route('admin.dashboard') }}" class="font-display text-2xl font-black tracking-tight text-white flex items-center gap-2">
                    <span class="text-accent">c</span>Farm<span class="text-stone font-sans text-sm tracking-normal font-normal ml-1 border border-stone/30 px-2 py-0.5 rounded-full">Admin</span>
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard',   'icon' => '📊', 'label' => 'Dashboard'],
                        ['route' => 'admin.users',        'icon' => '👥', 'label' => 'Users'],
                        ['route' => 'admin.listings',     'icon' => '📋', 'label' => 'Listings'],
                        ['route' => 'admin.scraper',      'icon' => '🤖', 'label' => 'Scraper'],
                        ['route' => 'admin.notifications','icon' => '🔔', 'label' => 'Notifications'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    @php
                        $isActive = request()->routeIs($item['route']);
                    @endphp
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all duration-200
                               {{ $isActive
                                    ? 'bg-primary text-white shadow-sm translate-x-1'
                                    : 'text-stone hover:bg-bark/30 hover:text-white' }}">
                        <span class="text-lg">{{ $item['icon'] }}</span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-4 py-4 border-t border-bark/30 bg-bark/10">
                <p class="text-xs text-stone mb-2 font-bold">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</p>
                <a href="{{ route('dashboard') }}"
                    class="text-xs font-bold text-accent hover:text-accent-dark transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to App
                </a>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col overflow-hidden bg-parchment/50">
            <header class="bg-white/80 backdrop-blur-md border-b border-stone px-8 py-5 flex items-center justify-between sticky top-0 z-10">
                <h1 class="font-display text-2xl font-bold text-soil">
                    {{ $header ?? 'Admin' }}
                </h1>
                <div class="flex items-center gap-4">
                    <span class="text-xs bg-red-100 text-red-700 px-3 py-1.5 rounded-full font-bold border border-red-200 shadow-sm flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        Admin Mode
                    </span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8 relative">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-primary-muted text-primary-dark border border-primary/20
                                rounded-xl text-sm font-bold shadow-sm flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200
                                rounded-xl text-sm font-bold shadow-sm flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
