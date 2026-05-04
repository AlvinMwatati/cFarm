@section('title', ($commodity->name ?? 'Commodity') . ' — cFarm')
@section('meta_description', 'View market prices, listings, and details for ' . ($commodity->name ?? 'this commodity') . ' on cFarm.')

<x-app-layout>
    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Back Link --}}
            <div>
                <a href="{{ route('commodities.index') }}" class="text-primary font-bold hover:underline text-sm">
                    ← Back to Directory
                </a>
            </div>

            {{-- Main Card --}}
            <div class="bg-white border border-stone shadow-sm rounded-[12px] overflow-hidden">
                
                {{-- Top Hero --}}
                <div class="bg-parchment p-8 border-b border-stone">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-primary-muted text-primary-dark px-3 py-1 rounded-full text-sm font-bold">
                            {{ $commodity->category->label() }}
                        </span>
                        <span class="text-sm text-bark font-mono">
                            Sold per {{ $commodity->unit->value }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="text-6xl filter drop-shadow-sm">{{ $commodity->icon ?? '🌾' }}</span>
                        <div>
                            <h1 class="font-display text-4xl sm:text-5xl font-black text-soil">{{ $commodity->name }}</h1>
                            @if ($commodity->description)
                                <p class="text-bark leading-relaxed mt-2 max-w-xl">{{ $commodity->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Stats Row --}}
                <div class="grid grid-cols-3 divide-x divide-stone border-b border-stone">
                    <div class="p-6 text-center">
                        <p class="text-xs text-bark uppercase font-bold tracking-widest mb-1">Listings</p>
                        <p class="font-mono text-2xl font-bold text-soil">{{ $listingCount ?? 0 }}</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-xs text-bark uppercase font-bold tracking-widest mb-1">Avg Price</p>
                        <p class="font-mono text-2xl font-bold text-soil">
                            @if(isset($avgPrice) && $avgPrice > 0)
                                <span class="text-sm text-bark font-sans mr-1">KES</span>{{ number_format($avgPrice, 0) }}
                            @else
                                —
                            @endif
                        </p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-xs text-bark uppercase font-bold tracking-widest mb-1">Counties</p>
                        <p class="font-mono text-2xl font-bold text-soil">{{ $countyCount ?? 0 }}</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="p-6 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('insights.show', $commodity) }}" class="btn-primary flex-1 justify-center text-center inline-flex items-center gap-2">
                        📊 View Price Insights
                    </a>
                    <a href="{{ route('listings.index', ['commodity_id' => $commodity->id]) }}" class="btn-secondary flex-1 justify-center text-center inline-flex items-center gap-2">
                        📋 View All Listings
                    </a>
                </div>
            </div>

            {{-- Follow / Unfollow --}}
            @auth
                <div class="bg-white border border-stone rounded-[12px] p-6 shadow-sm">
                    @if (auth()->user()->isFollowingCommodity($commodity))
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-bold text-soil text-sm">🔔 You're following {{ $commodity->name }}</p>
                                <p class="text-xs text-bark mt-0.5">You'll receive alerts when prices change.</p>
                            </div>
                            <form method="POST" action="{{ route('commodities.unfollow', $commodity) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-secondary text-sm px-4 py-2">
                                    Unfollow
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-bold text-soil text-sm">Get price alerts for {{ $commodity->name }}</p>
                                <p class="text-xs text-bark mt-0.5">Be notified when prices change or new listings appear.</p>
                            </div>
                            <form method="POST" action="{{ route('commodities.follow', $commodity) }}">
                                @csrf
                                <button type="submit" class="btn-primary text-sm px-6 py-2">
                                    🔔 Follow
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endauth

            {{-- Recent Listings --}}
            @if (isset($recentListings) && $recentListings->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-2xl font-bold text-soil flex items-center gap-2">
                            <span>📋</span> Recent Listings
                        </h2>
                        <a href="{{ route('listings.index', ['commodity_id' => $commodity->id]) }}" class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">
                            View all →
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach ($recentListings->take(6) as $listing)
                            <a href="{{ route('listings.show', $listing) }}" class="block group">
                                <div class="bg-white border border-stone rounded-[12px] p-5 hover:border-primary transition-all duration-200 shadow-sm hover:shadow-md">
                                    <h4 class="font-bold text-soil group-hover:text-primary transition-colors line-clamp-1 mb-2">{{ $listing->title }}</h4>
                                    <p class="text-xs text-bark mb-3 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $listing->county }} • {{ $listing->created_at->diffForHumans() }}
                                    </p>
                                    <div class="flex justify-between items-center pt-3 border-t border-stone/30">
                                        <span class="font-mono font-bold text-soil">
                                            <span class="text-xs text-bark font-sans mr-1">KES</span>{{ number_format($listing->price_per_unit, 0) }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider {{ $listing->status->value === 'active' ? 'bg-secondary-muted text-secondary-dark' : 'bg-stone/30 text-bark' }}">
                                            {{ $listing->status->label() }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
