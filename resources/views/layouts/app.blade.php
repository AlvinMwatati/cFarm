<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'cFarm') . ' — Kenya\'s Farm Marketplace')</title>
        <meta name="description" content="@yield('meta_description', 'Kenya\'s farm produce marketplace — real KAMIS government prices, direct farmer-to-buyer connections.')">
        <meta property="og:title" content="@yield('title', 'cFarm — Kenya\'s Farm Marketplace')">
        <meta property="og:description" content="@yield('meta_description', 'Compare real government market prices and connect directly with farmers across Kenya.')">
        <meta property="og:type" content="website">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')

        <!-- Global Styles -->
        <style>
            :root {
                --color-primary:        #C1440E;
                --color-primary-dark:   #8B3008;
                --color-primary-light:  #E8693A;
                --color-primary-muted:  #F4DDD3;

                --color-secondary:      #2D5016;
                --color-secondary-dark: #1A2F0D;
                --color-secondary-light:#4A7A28;
                --color-secondary-muted:#D6E8C4;

                --color-accent:         #D4A017;
                --color-accent-light:   #F5E6A3;

                --color-soil:           #3D2B1F;
                --color-bark:           #6B4C35;
                --color-sand:           #F5EFE6;
                --color-parchment:      #FBF7F2;
                --color-stone:          #C4B5A5;
                --color-mist:           #EAE4DC;

                --color-up:             #2D8A4E;
                --color-down:           #C1440E;
                --color-neutral:        #8A7968;

                --font-display: 'Playfair Display', Georgia, serif;
                --font-body:    'DM Sans', sans-serif;
                --font-mono:    'DM Mono', monospace;

                --radius-sm:  4px;
                --radius-md:  8px;
                --radius-lg:  12px;
                --radius-xl:  20px;
                --radius-full: 9999px;

                --shadow-sm: 0 1px 3px rgba(61,43,31,0.08), 0 1px 2px rgba(61,43,31,0.04);
                --shadow-md: 0 4px 12px rgba(61,43,31,0.10), 0 2px 4px rgba(61,43,31,0.06);
                --shadow-lg: 0 8px 24px rgba(61,43,31,0.12), 0 4px 8px rgba(61,43,31,0.08);
            }

            body::before {
                content: '';
                position: fixed;
                inset: 0;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
                pointer-events: none;
                z-index: 0;
                opacity: 0.4;
            }

            .cfarm-logo {
                font-family: var(--font-display);
                font-weight: 900;
                font-size: 1.5rem;
                text-decoration: none;
                letter-spacing: -0.02em;
            }
            .logo-c {
                color: var(--color-primary);
                font-style: italic;
            }
            .logo-farm {
                color: var(--color-soil);
            }
            .logo-inverted .logo-farm { color: #FBF7F2; }

            .btn-primary {
                background: var(--color-primary); color: white;
                font-family: var(--font-body); font-weight: 600; font-size: 0.875rem;
                padding: 0.625rem 1.25rem; border-radius: var(--radius-md);
                border: none; cursor: pointer; transition: all 0.15s ease;
            }
            .btn-primary:hover { background: var(--color-primary-dark); transform: translateY(-1px); box-shadow: var(--shadow-md); }
            
            .btn-secondary {
                background: transparent; color: var(--color-soil);
                border: 1.5px solid var(--color-stone); font-family: var(--font-body);
                font-weight: 500; font-size: 0.875rem; padding: 0.625rem 1.25rem;
                border-radius: var(--radius-md); cursor: pointer; transition: all 0.15s ease;
            }
            .btn-secondary:hover { border-color: var(--color-primary); color: var(--color-primary); }
            
            .btn-danger {
                background: #AC3149; color: white;
                font-family: var(--font-body); font-weight: 600; font-size: 0.875rem;
                padding: 0.625rem 1.25rem; border-radius: var(--radius-md);
                border: none; cursor: pointer; transition: all 0.15s ease;
            }

            .price-badge { font-family: var(--font-mono); font-weight: 500; color: var(--color-soil); }
            .price-badge .unit { font-size: 0.8em; color: var(--color-bark); margin-left: 2px; }

            .stat-card { background: var(--color-parchment); border-radius: var(--radius-lg); border: 1px solid var(--color-stone); padding: 1.25rem; }
            .stat-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-bark); font-family: var(--font-body); }
            .stat-value { font-family: var(--font-mono); font-size: 1.75rem; font-weight: 500; color: var(--color-soil); margin: 0.25rem 0; }
            .stat-change { font-size: 0.8rem; font-family: var(--font-mono); }
            .stat-change.up   { color: var(--color-up); }
            .stat-change.down { color: var(--color-down); }
        </style>
    </head>
    <body class="font-sans antialiased text-bark bg-sand">
        <div class="min-h-screen relative z-10 flex flex-col">
            @include('layouts.navigation')

            {{-- Flash Messages --}}
            @if (session('success') || session('error') || session('warning'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="fixed top-4 right-4 z-[9999] max-w-sm animate-slide-down">
                    @if(session('success'))
                        <div class="toast toast-success flex items-center gap-3">
                            <span class="text-lg">✅</span>
                            <span>{{ session('success') }}</span>
                            <button @click="show = false" class="ml-auto text-lg opacity-60 hover:opacity-100">✕</button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="toast toast-error flex items-center gap-3">
                            <span class="text-lg">❌</span>
                            <span>{{ session('error') }}</span>
                            <button @click="show = false" class="ml-auto text-lg opacity-60 hover:opacity-100">✕</button>
                        </div>
                    @endif
                    @if(session('warning'))
                        <div class="toast flex items-center gap-3" style="background:#FEF3C7;color:#92400E;border:1px solid #FBBF24">
                            <span class="text-lg">⚠️</span>
                            <span>{{ session('warning') }}</span>
                            <button @click="show = false" class="ml-auto text-lg opacity-60 hover:opacity-100">✕</button>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-parchment border-b border-stone">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow animate-fade-in">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="bg-soil text-parchment/80 mt-16 border-t border-stone/20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        {{-- Brand --}}
                        <div class="md:col-span-1">
                            <div class="cfarm-logo logo-inverted mb-3">
                                <span class="logo-c">c</span><span class="logo-farm">Farm</span>
                            </div>
                            <p class="text-sm text-parchment/50">Kenya's farm produce marketplace — real government prices, direct connections.</p>
                        </div>
                        {{-- Quick Links --}}
                        <div>
                            <h4 class="font-bold text-sm uppercase tracking-widest text-parchment/70 mb-4">Marketplace</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('listings.index') }}" class="hover:text-primary-light transition-colors">Browse Listings</a></li>
                                <li><a href="{{ route('insights.index') }}" class="hover:text-primary-light transition-colors">Market Insights</a></li>
                                <li><a href="{{ route('commodities.index') }}" class="hover:text-primary-light transition-colors">Commodity Directory</a></li>
                            </ul>
                        </div>
                        {{-- Account --}}
                        <div>
                            <h4 class="font-bold text-sm uppercase tracking-widest text-parchment/70 mb-4">Account</h4>
                            <ul class="space-y-2 text-sm">
                                @auth
                                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary-light transition-colors">Dashboard</a></li>
                                    <li><a href="{{ route('listings.mine') }}" class="hover:text-primary-light transition-colors">My Listings</a></li>
                                    <li><a href="{{ route('profile.edit') }}" class="hover:text-primary-light transition-colors">Profile Settings</a></li>
                                @else
                                    <li><a href="{{ route('login') }}" class="hover:text-primary-light transition-colors">Log In</a></li>
                                    <li><a href="{{ route('register') }}" class="hover:text-primary-light transition-colors">Register</a></li>
                                @endauth
                            </ul>
                        </div>
                        {{-- Data Source --}}
                        <div>
                            <h4 class="font-bold text-sm uppercase tracking-widest text-parchment/70 mb-4">Data Source</h4>
                            <p class="text-sm text-parchment/50">Market prices sourced from KAMIS — Kenya Agricultural Market Information System.</p>
                            <p class="text-xs text-parchment/30 mt-3 font-mono">© {{ date('Y') }} cFarm</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        {{-- Scroll to top button --}}
        <button x-data="{ visible: false }" @scroll.window="visible = window.scrollY > 400" x-show="visible" x-transition @click="window.scrollTo({top:0, behavior:'smooth'})" class="scroll-top-btn visible" x-cloak>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
        </button>

        @stack('scripts')
    </body>
</html>
