<x-admin-layout header="Dashboard">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach ([
            ['label' => 'Total Users',      'value' => $stats['total_users'],       'icon' => '👥', 'color' => 'indigo'],
            ['label' => 'Active Listings',  'value' => $stats['active_listings'],   'icon' => '📋', 'color' => 'green'],
            ['label' => 'Commodities',      'value' => $stats['total_commodities'], 'icon' => '🌽', 'color' => 'yellow'],
            ['label' => 'Price Records',    'value' => number_format($stats['total_prices']), 'icon' => '📈', 'color' => 'purple'],
        ] as $stat)
            <div class="bg-white border border-stone rounded-xl shadow-sm p-6 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-6xl opacity-5 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                    {{ $stat['icon'] }}
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="font-mono text-xs text-bark uppercase tracking-widest mb-1">
                            {{ $stat['label'] }}
                        </p>
                        <p class="font-display text-4xl font-bold text-soil">
                            {{ $stat['value'] }}
                        </p>
                    </div>
                    <span class="text-3xl filter drop-shadow-sm">{{ $stat['icon'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- KAMIS Scraper Status --}}
        <div class="bg-white border border-stone rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6 border-b border-stone pb-4">
                <h3 class="font-display text-xl font-bold text-soil flex items-center gap-2">🤖 KAMIS Scraper</h3>
                <a href="{{ route('admin.scraper') }}"
                    class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">
                    View logs →
                </a>
            </div>
            @if ($lastScrape)
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-stone/30">
                        <span class="text-bark font-bold">Last run</span>
                        <span class="font-mono text-soil bg-stone/20 px-2 py-1 rounded">
                            {{ $lastScrape->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-stone/30">
                        <span class="text-bark font-bold">Status</span>
                        <span class="font-bold px-2 py-1 rounded-lg {{ $lastScrape->success ? 'bg-primary-muted text-primary-dark' : 'bg-red-100 text-red-700' }}">
                            {{ $lastScrape->success ? '✅ Success' : '❌ Failed' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-stone/30">
                        <span class="text-bark font-bold">Rows saved</span>
                        <span class="font-mono text-soil bg-stone/20 px-2 py-1 rounded">
                            {{ number_format($lastScrape->rows_saved) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-stone/30">
                        <span class="text-bark font-bold">Duration</span>
                        <span class="font-mono text-soil bg-stone/20 px-2 py-1 rounded">
                            {{ $lastScrape->duration_seconds }}s
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.scraper.run') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="btn-primary w-full justify-center">
                        Run Scraper Now
                    </button>
                </form>
            @else
                <p class="text-sm text-bark py-4">No scrape runs yet.</p>
                <form method="POST" action="{{ route('admin.scraper.run') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="btn-primary w-full justify-center">
                        Run First Scrape
                    </button>
                </form>
            @endif
        </div>

        {{-- User Stats --}}
        <div class="bg-white border border-stone rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6 border-b border-stone pb-4">
                <h3 class="font-display text-xl font-bold text-soil flex items-center gap-2">👥 Recent Users</h3>
                <a href="{{ route('admin.users') }}"
                    class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">
                    Manage →
                </a>
            </div>
            <div class="space-y-0 divide-y divide-stone/30">
                @foreach ($recentUsers as $user)
                    <div class="flex items-center justify-between py-3 group">
                        <div>
                            <p class="font-bold text-soil group-hover:text-primary transition-colors">
                                {{ $user->name }}
                                @if($user->is_banned)
                                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full ml-2 font-bold uppercase tracking-wide">Banned</span>
                                @endif
                            </p>
                            <p class="text-xs text-bark mt-0.5 flex items-center gap-1">
                                <svg class="w-3 h-3 text-stone" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $user->county }} <span class="text-stone px-1">•</span> {{ $user->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <a href="{{ route('admin.users.show', $user) }}"
                            class="text-sm font-bold text-primary opacity-0 group-hover:opacity-100 transition-opacity">View</a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Listings --}}
        <div class="bg-white border border-stone rounded-xl shadow-sm p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-6 border-b border-stone pb-4">
                <h3 class="font-display text-xl font-bold text-soil flex items-center gap-2">📋 Recent Listings</h3>
                <a href="{{ route('admin.listings') }}"
                    class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">
                    View all →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs font-bold text-bark uppercase bg-parchment/50 border-b border-stone">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">Title</th>
                            <th class="px-4 py-3">Seller</th>
                            <th class="px-4 py-3">Commodity</th>
                            <th class="px-4 py-3">Price</th>
                            <th class="px-4 py-3 rounded-tr-lg">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone">
                        @foreach ($recentListings as $listing)
                            <tr class="hover:bg-parchment/30 transition-colors">
                                <td class="px-4 py-3 font-bold text-soil">{{ Str::limit($listing->title, 40) }}</td>
                                <td class="px-4 py-3 text-bark">{{ $listing->user->name }}</td>
                                <td class="px-4 py-3 text-bark flex items-center gap-2">
                                    <span>{{ $listing->commodity->icon }}</span> {{ $listing->commodity->name }}
                                </td>
                                <td class="px-4 py-3 font-mono font-bold text-soil">
                                    <span class="text-xs text-bark font-sans font-normal mr-1">KES</span>{{ number_format($listing->price_per_unit, 0) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider
                                        {{ $listing->status->value === 'active'
                                            ? 'bg-primary-muted text-primary-dark'
                                            : 'bg-stone/30 text-bark' }}">
                                        {{ $listing->status->label() }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>