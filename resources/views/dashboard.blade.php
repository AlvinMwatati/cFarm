@section('title', 'Dashboard — cFarm')
@section('meta_description', 'Your cFarm dashboard — track listings, followed commodities, and price alerts.')

<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Greeting --}}
            @php
                $hour = now()->format('H');
                if ($hour < 12) $greeting = 'Good morning';
                elseif ($hour < 17) $greeting = 'Good afternoon';
                else $greeting = 'Good evening';
            @endphp
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="font-display text-3xl sm:text-4xl font-bold text-soil">
                        {{ $greeting }}, {{ auth()->user()->name }} 👋
                    </h1>
                    <p class="text-bark mt-1 text-lg">Here's what's happening in your followed markets today.</p>
                </div>
                <a href="{{ route('listings.create') }}" class="btn-primary inline-flex items-center gap-2 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Listing
                </a>
            </div>

            {{-- Stat Cards --}}
            @php
                $statCards = [
                    [
                        'label' => 'Active Listings',
                        'value' => $stats['active_listings'] ?? 0,
                        'icon'  => '📋',
                        'trend' => null,
                        'href'  => route('listings.mine'),
                    ],
                    [
                        'label' => 'Total Listings',
                        'value' => $stats['total_listings'] ?? 0,
                        'icon'  => '📊',
                        'trend' => null,
                        'href'  => route('listings.mine'),
                    ],
                    [
                        'label' => 'Followed Commodities',
                        'value' => $stats['followed_commodities'] ?? 0,
                        'icon'  => '🔔',
                        'trend' => null,
                        'href'  => route('follows.index'),
                    ],
                    [
                        'label' => 'Unread Alerts',
                        'value' => $stats['unread_notifications'] ?? 0,
                        'icon'  => '⚡',
                        'trend' => null,
                        'href'  => route('notifications.index'),
                    ],
                ];
            @endphp
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($statCards as $stat)
                    <a href="{{ $stat['href'] }}" class="stat-card group hover:border-primary transition-all duration-200 relative overflow-hidden block">
                        <div class="absolute -right-3 -bottom-3 text-5xl opacity-5 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                            {{ $stat['icon'] }}
                        </div>
                        <p class="stat-label mb-1">{{ $stat['label'] }}</p>
                        <p class="stat-value">{{ $stat['value'] }}</p>
                        <div class="mt-2 text-xs text-primary font-bold opacity-0 group-hover:opacity-100 transition-opacity">
                            View →
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Two-column layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                {{-- Left: Price Alerts Feed --}}
                <div class="lg:col-span-3 space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-2xl font-bold text-soil flex items-center gap-2">
                            <span>📡</span> My Price Alerts
                        </h2>
                        <a href="{{ route('notifications.index') }}" class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">View all →</a>
                    </div>

                    @if (isset($recentAlerts) && $recentAlerts->isNotEmpty())
                        <div class="space-y-3">
                            @foreach ($recentAlerts->take(8) as $alert)
                                @php $data = $alert->data; @endphp
                                <div class="bg-white border border-stone rounded-[12px] p-4 flex items-start gap-4 transition-all duration-200 hover:shadow-sm
                                    {{ !$alert->read_at ? 'border-l-4 border-l-primary' : '' }}">
                                    <div class="text-2xl mt-0.5 shrink-0">
                                        @if(($data['type'] ?? '') === 'price_drop') 📉
                                        @elseif(($data['type'] ?? '') === 'price_spike') 📈
                                        @elseif(($data['type'] ?? '') === 'new_listing') 🌾
                                        @elseif(($data['type'] ?? '') === 'weekly_summary') 📊
                                        @else 🔔
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-soil text-sm leading-tight">
                                            {{ $data['message'] ?? 'Notification' }}
                                        </p>
                                        <p class="text-xs text-bark font-mono mt-1">
                                            {{ $alert->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <span class="empty-icon">📭</span>
                            <h4 class="empty-title">No alerts yet</h4>
                            <p class="empty-desc">Follow commodities to receive price alerts when the market moves.</p>
                            <a href="{{ route('commodities.index') }}" class="btn-primary inline-flex">Browse Commodities</a>
                        </div>
                    @endif
                </div>

                {{-- Right: Sidebar --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Recently Active Commodities --}}
                    <div>
                        <h3 class="font-display text-lg font-bold text-soil mb-4 flex items-center gap-2">
                            <span>🌽</span> Active Commodities
                        </h3>
                        @if (isset($activeCommodities) && $activeCommodities->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach ($activeCommodities->take(12) as $commodity)
                                    <a href="{{ route('insights.show', $commodity) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-stone rounded-full text-sm font-bold text-soil hover:border-primary hover:text-primary transition-all duration-200">
                                        <span>{{ $commodity->icon ?? '🌾' }}</span>
                                        {{ $commodity->name }}
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-bark bg-parchment rounded-lg p-4 border border-stone/50">No commodities tracked yet.</p>
                        @endif
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-white border border-stone rounded-[12px] p-6 space-y-3">
                        <h3 class="font-display text-lg font-bold text-soil mb-4 flex items-center gap-2">
                            <span>⚡</span> Quick Actions
                        </h3>
                        <a href="{{ route('listings.create') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-parchment transition-colors group">
                            <span class="text-xl">📝</span>
                            <div>
                                <p class="font-bold text-soil text-sm group-hover:text-primary transition-colors">Post a Listing</p>
                                <p class="text-xs text-bark">Sell your produce to buyers</p>
                            </div>
                        </a>
                        <a href="{{ route('insights.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-parchment transition-colors group">
                            <span class="text-xl">📈</span>
                            <div>
                                <p class="font-bold text-soil text-sm group-hover:text-primary transition-colors">Check Market Prices</p>
                                <p class="text-xs text-bark">KAMIS data across Kenya</p>
                            </div>
                        </a>
                        <a href="{{ route('follows.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-parchment transition-colors group">
                            <span class="text-xl">🔔</span>
                            <div>
                                <p class="font-bold text-soil text-sm group-hover:text-primary transition-colors">Manage Alerts</p>
                                <p class="text-xs text-bark">Set price thresholds</p>
                            </div>
                        </a>
                    </div>

                    {{-- My Followed Commodities --}}
                    @if (isset($followedCommodities) && $followedCommodities->isNotEmpty())
                        <div class="bg-white border border-stone rounded-[12px] p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-display text-lg font-bold text-soil flex items-center gap-2">
                                    <span>👁️</span> Watching
                                </h3>
                                <a href="{{ route('follows.index') }}" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">Edit →</a>
                            </div>
                            <div class="space-y-3">
                                @foreach ($followedCommodities->take(5) as $follow)
                                    <a href="{{ route('insights.show', $follow->commodity) }}" class="flex items-center justify-between group">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xl">{{ $follow->commodity->icon ?? '🌾' }}</span>
                                            <span class="font-bold text-sm text-soil group-hover:text-primary transition-colors">{{ $follow->commodity->name }}</span>
                                        </div>
                                        <span class="text-xs font-mono text-bark bg-parchment px-2 py-0.5 rounded">±{{ $follow->price_change_threshold }}%</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
