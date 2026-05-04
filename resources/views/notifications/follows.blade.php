@section('title', 'Price Alerts — cFarm')

<x-app-layout>
    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Header --}}
            <div class="border-b border-stone pb-6">
                <h1 class="font-display text-3xl sm:text-4xl font-bold text-soil">Price Alerts</h1>
                <p class="text-bark mt-2 text-lg">Manage your followed commodities and notification preferences.</p>
            </div>

            @if ($follows->isEmpty())
                <div class="empty-state" style="padding: 5rem 2rem;">
                    <span class="empty-icon" style="font-size: 4.5rem;">🌾</span>
                    <h4 class="empty-title">Your watchlist is empty</h4>
                    <p class="empty-desc">Follow commodities to get real-time alerts when prices change or new listings appear.</p>
                    <a href="{{ route('insights.index') }}" class="btn-primary inline-flex px-8 py-3">
                        Explore Market Data
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($follows as $follow)
                        <div class="bg-white border border-stone rounded-[12px] p-6 hover:border-primary/40 transition-all duration-200 hover:shadow-md flex flex-col justify-between group">
                            {{-- Top: Commodity info --}}
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="text-4xl filter drop-shadow-sm">{{ $follow->commodity->icon ?? '🌾' }}</span>
                                    <div>
                                        <h3 class="font-bold text-lg text-soil group-hover:text-primary transition-colors">{{ $follow->commodity->name }}</h3>
                                        <p class="text-xs text-bark font-mono uppercase tracking-widest">{{ $follow->commodity->category->value ?? '' }}</p>
                                    </div>
                                </div>

                                {{-- Alert types --}}
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @if($follow->notify_price_drop) 
                                        <span class="px-2 py-1 bg-down/10 text-down rounded text-[10px] font-bold uppercase tracking-wide">📉 Price Drop</span>
                                    @endif
                                    @if($follow->notify_price_spike) 
                                        <span class="px-2 py-1 bg-up/10 text-up rounded text-[10px] font-bold uppercase tracking-wide">📈 Price Spike</span>
                                    @endif
                                    @if($follow->notify_new_listing) 
                                        <span class="px-2 py-1 bg-primary-muted text-primary-dark rounded text-[10px] font-bold uppercase tracking-wide">🌾 New Listings</span>
                                    @endif
                                    @if($follow->notify_weekly_summary) 
                                        <span class="px-2 py-1 bg-stone/20 text-bark rounded text-[10px] font-bold uppercase tracking-wide">📊 Weekly</span>
                                    @endif
                                </div>

                                {{-- Threshold --}}
                                <div class="bg-parchment rounded-lg p-3 border border-stone/30 text-center mb-4">
                                    <p class="text-xs text-bark uppercase font-bold tracking-widest mb-1">Alert Threshold</p>
                                    <p class="font-mono text-xl font-bold text-soil">±{{ $follow->price_change_threshold }}%</p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center justify-between pt-4 border-t border-stone/30">
                                <a href="{{ route('insights.show', $follow->commodity) }}" class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">
                                    View Insights →
                                </a>
                                <form method="POST" action="{{ route('commodities.unfollow', $follow->commodity) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1" onclick="return confirm('Unfollow {{ $follow->commodity->name }}?')" title="Unfollow">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Follow More CTA --}}
                <div class="text-center pt-6">
                    <a href="{{ route('commodities.index') }}" class="btn-secondary inline-flex items-center gap-2 px-8 py-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Follow More Commodities
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
