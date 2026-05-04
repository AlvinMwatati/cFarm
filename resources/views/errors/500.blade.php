<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Error — cFarm</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --color-primary: #C1440E;
            --color-soil: #3D2B1F;
            --color-bark: #6B4C35;
            --color-parchment: #FBF7F2;
            --color-stone: #C4B5A5;
            --font-display: 'Playfair Display', Georgia, serif;
            --font-body: 'DM Sans', sans-serif;
        }
        body {
            font-family: var(--font-body);
            background: var(--color-parchment);
            color: var(--color-soil);
        }
        .btn-primary {
            background: var(--color-primary); color: white;
            font-weight: 600; font-size: 0.875rem;
            padding: 0.75rem 1.5rem; border-radius: 8px;
            display: inline-flex; align-items: center; gap: 0.5rem;
            text-decoration: none; transition: all 0.15s ease;
        }
        .btn-primary:hover { background: #8B3008; transform: translateY(-1px); }
        .btn-secondary {
            background: transparent; color: var(--color-soil);
            border: 1.5px solid var(--color-stone);
            font-weight: 500; font-size: 0.875rem;
            padding: 0.75rem 1.5rem; border-radius: 8px;
            display: inline-flex; align-items: center; gap: 0.5rem;
            text-decoration: none; transition: all 0.15s ease; cursor: pointer;
        }
        .btn-secondary:hover { border-color: var(--color-primary); color: var(--color-primary); }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="text-8xl mb-6" style="filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));">🌧️</div>
        <h1 style="font-family: var(--font-display);" class="text-5xl font-bold mb-4">500</h1>
        <h2 style="font-family: var(--font-display);" class="text-2xl font-bold mb-3">Server Trouble</h2>
        <p class="text-lg mb-8" style="color: var(--color-bark);">Something went wrong on our end. Our team has been notified. Please try again shortly.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="btn-primary">🏠 Back to Home</a>
            <button onclick="window.location.reload()" class="btn-secondary">🔄 Try Again</button>
        </div>
    </div>
</body>
</html>
