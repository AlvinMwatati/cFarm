@section('title', 'Market Insights — cFarm')
@section('meta_description', 'Real-time KAMIS government market prices for farm commodities across Kenya. Compare prices, track trends, make informed decisions.')

<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Hero Section --}}
            <div class="bg-soil rounded-[16px] p-8 sm:p-12 relative overflow-hidden">
                {{-- Decorative stripes --}}
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute -right-20 -top-20 w-64 h-64 border-[40px] border-primary rounded-full"></div>
                    <div class="absolute -left-10 -bottom-10 w-48 h-48 border-[30px] border-accent rounded-full"></div>
                </div>
                <div class="relative z-10">
                    <p class="font-mono text-xs text-primary-light uppercase tracking-[0.3em] mb-3">Kenya Agricultural Market Information</p>
                    <h1 class="font-display text-3xl sm:text-5xl font-bold text-parchment leading-tight mb-4">
                        Market Insights
                    </h1>
                    <p class="text-parchment/60 max-w-xl text-lg">
                        Real KAMIS government prices across all 47 counties. Track trends, compare prices, and make informed decisions.
                    </p>
                    <div class="mt-4 inline-flex items-center gap-2 bg-parchment/10 text-parchment/80 px-3 py-1.5 rounded-full text-xs font-mono">
                        🕐 Last synced: {{ now()->format('M d, Y h:i A') }}
                    </div>
                </div>
            </div>

            {{-- Platform Highlights --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $highlights = [
                        ['label' => 'Commodities Tracked', 'value' => $commodities->count(), 'icon' => '🌽'],
                        ['label' => 'Counties Covered', 'value' => '47', 'icon' => '🗺️'],
                        ['label' => 'Active Listings', 'value' => $activeListingsCount ?? '—', 'icon' => '📋'],
                        ['label' => 'Price Updates', 'value' => $priceRecordsCount ?? '—', 'icon' => '📈'],
                    ];
                @endphp
                @foreach ($highlights as $hl)
                    <div class="stat-card group hover:border-primary transition-all duration-200 relative overflow-hidden">
                        <div class="absolute -right-3 -bottom-3 text-5xl opacity-5 group-hover:scale-110 transition-transform duration-300 pointer-events-none">{{ $hl['icon'] }}</div>
                        <p class="stat-label mb-1">{{ $hl['label'] }}</p>
                        <p class="stat-value">{{ $hl['value'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Search & Filters --}}
            <div class="bg-white border border-stone rounded-[12px] p-4 shadow-sm" x-data="{ search: '{{ request('search', '') }}' }">
                <form method="GET" action="{{ route('insights.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-3 w-5 h-5 text-bark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="search" x-model="search" placeholder="Search commodities..." 
                               class="w-full pl-10 rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" value="{{ request('search') }}">
                    </div>
                    <div class="flex gap-3">
                        <select name="category" class="w-full md:w-48 rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white">
                            <option value="">All Categories</option>
                            @foreach ($categories ?? [] as $cat)
                                <option value="{{ $cat->value }}" {{ request('category') === $cat->value ? 'selected' : '' }}>{{ $cat->label() }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-primary shrink-0 px-6">Filter</button>
                    </div>
                </form>
            </div>

            {{-- Grid of Commodities --}}
            @if ($commodities->isEmpty())
                <div class="empty-state">
                    <span class="empty-icon">📉</span>
                    <h4 class="empty-title">No price data found</h4>
                    <p class="empty-desc">We couldn't find any price records matching your filters. Try adjusting your search.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($commodities as $commodity)
                        <a href="{{ route('insights.show', $commodity) }}" class="block group">
                            <div class="stat-card hover:border-primary transition-all duration-200 h-full flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-4">
                                        @php
                                            $icon = $commodity->icon ?? '🌾';
                                            
                                            // Use real price data if available
                                            $latestPrice = $commodity->latestPrice ?? null;
                                            $prevPrice = $commodity->previousPrice ?? null;
                                            $price = $latestPrice->price ?? null;
                                            $change = null;
                                            $trend = 'neutral';
                                            
                                            if ($latestPrice && $prevPrice && $prevPrice->price > 0) {
                                                $change = (($latestPrice->price - $prevPrice->price) / $prevPrice->price) * 100;
                                                $trend = $change > 1 ? 'up' : ($change < -1 ? 'down' : 'neutral');
                                            }
                                        @endphp
                                        <span class="text-4xl filter drop-shadow-sm">{{ $icon }}</span>
                                        
                                        @if($change !== null)
                                            @if($trend === 'up')
                                                <span class="bg-up/10 px-2 py-1 rounded text-up text-xs font-mono font-bold">▲ {{ number_format(abs($change), 1) }}%</span>
                                            @elseif($trend === 'down')
                                                <span class="bg-down/10 px-2 py-1 rounded text-down text-xs font-mono font-bold">▼ {{ number_format(abs($change), 1) }}%</span>
                                            @else
                                                <span class="bg-stone/20 px-2 py-1 rounded text-bark text-xs font-mono font-bold">— steady</span>
                                            @endif
                                        @else
                                            <span class="bg-stone/20 px-2 py-1 rounded text-bark text-xs font-mono font-bold">—</span>
                                        @endif
                                    </div>
                                    <h3 class="font-display font-bold text-lg text-soil mb-1 line-clamp-1">{{ $commodity->name }}</h3>
                                    <p class="text-xs text-bark">{{ $commodity->category->label() }}</p>
                                </div>
                                
                                <div class="mt-4 pt-3 border-t border-stone/30">
                                    @if ($price)
                                        <div class="font-mono text-2xl font-medium text-soil">
                                            <span class="text-xs text-bark font-sans">KES</span> {{ number_format($price, 2) }}
                                            <span class="text-xs text-bark font-sans">/{{ $commodity->unit->value }}</span>
                                        </div>
                                    @else
                                        <div class="text-sm text-bark font-mono">No price data yet</div>
                                    @endif
                                    <div class="mt-3 text-primary text-sm font-bold opacity-0 group-hover:opacity-100 transition-opacity">
                                        View History →
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
