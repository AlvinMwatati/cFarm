<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'cFarm') }} — @yield('title', 'Welcome')</title>
        <meta name="description" content="Kenya's farm produce marketplace — real KAMIS government prices, direct farmer-to-buyer connections.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

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

                --font-display: 'Playfair Display', Georgia, serif;
                --font-body:    'DM Sans', sans-serif;
                --font-mono:    'DM Mono', monospace;

                --radius-sm:  4px;
                --radius-md:  8px;
                --radius-lg:  12px;
            }

            body {
                font-family: var(--font-body);
                background: linear-gradient(135deg, var(--color-soil) 0%, #2A1E17 40%, var(--color-primary-dark) 100%);
                color: var(--color-soil);
                min-height: 100vh;
                position: relative;
            }

            /* Grain texture overlay */
            body::before {
                content: "";
                position: fixed;
                inset: 0;
                pointer-events: none;
                z-index: 1;
                opacity: 0.3;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            }

            /* Floating price data pattern */
            body::after {
                content: "KES 45.50  •  KES 82.00  •  KES 35.00  •  KAMIS  •  47 Counties  •  KES 120.00  •  KES 60.00  •  Nairobi  •  Meru  •  Nakuru";
                position: fixed;
                inset: 0;
                font-family: var(--font-mono);
                font-size: 0.75rem;
                color: rgba(255,255,255,0.03);
                word-spacing: 2rem;
                line-height: 3;
                overflow: hidden;
                pointer-events: none;
                z-index: 1;
                padding: 2rem;
                letter-spacing: 0.5em;
            }

            .cfarm-logo {
                font-family: var(--font-display);
                font-weight: 900;
                font-size: 2rem;
                text-decoration: none;
                letter-spacing: -0.02em;
            }
            .logo-c {
                color: var(--color-primary-light);
                font-style: italic;
            }
            .logo-farm {
                color: var(--color-parchment);
            }

            .btn-primary {
                background: var(--color-primary);
                color: white;
                font-family: var(--font-body);
                font-weight: 600;
                font-size: 0.875rem;
                padding: 0.75rem 1.5rem;
                border-radius: var(--radius-md);
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 100%;
            }
            .btn-primary:hover {
                background: var(--color-primary-dark);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(193,68,14,0.3);
            }

            .auth-card {
                background: var(--color-parchment);
                border: 1px solid var(--color-stone);
                border-radius: var(--radius-lg);
                box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.05);
                backdrop-filter: blur(2px);
            }

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(16px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.5s ease-out;
            }
        </style>
    </head>
    <body class="antialiased min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="relative z-10 w-full sm:max-w-md animate-fade-in-up">
            <!-- Logo -->
            <div class="mb-8 text-center">
                <a href="/" class="inline-block group">
                    <div class="cfarm-logo">
                        <span class="logo-c">c</span><span class="logo-farm">Farm</span>
                    </div>
                    <p class="font-mono text-xs text-stone mt-1 uppercase tracking-widest">Kenya's Farm Marketplace</p>
                </a>
            </div>

            <!-- Auth Card -->
            <div class="auth-card p-8">
                {{ $slot }}
            </div>

            <!-- Footer note -->
            <p class="text-center text-xs text-stone/60 mt-8">
                Price data sourced from KAMIS — Kenya Agricultural Market Information System
            </p>
        </div>
        
    </body>
</html>
