@section('title', 'Notifications — cFarm')

<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <h1 class="font-display text-3xl font-bold text-soil">Notifications</h1>
                    <p class="text-bark mt-1">Updates on your followed commodities and marketplace alerts.</p>
                </div>
                @if(auth()->user()->notifications->isNotEmpty())
                    <div class="flex items-center gap-4">
                        @if(auth()->user()->unreadNotifications->isNotEmpty())
                            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">
                                    ✓ Mark all read
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('notifications.destroyAll') }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm font-bold text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('Clear all notifications?')">
                                Clear all
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Filter Tabs --}}
            <div x-data="{ filter: 'all' }" class="space-y-6">
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    <button @click="filter = 'all'" :class="filter === 'all' ? 'active' : ''" class="filter-tab">All</button>
                    <button @click="filter = 'price_drop'" :class="filter === 'price_drop' ? 'active' : ''" class="filter-tab">📉 Price Drops</button>
                    <button @click="filter = 'price_spike'" :class="filter === 'price_spike' ? 'active' : ''" class="filter-tab">📈 Price Spikes</button>
                    <button @click="filter = 'new_listing'" :class="filter === 'new_listing' ? 'active' : ''" class="filter-tab">🌾 New Listings</button>
                    <button @click="filter = 'weekly_summary'" :class="filter === 'weekly_summary' ? 'active' : ''" class="filter-tab">📊 Weekly</button>
                </div>

                @if ($notifications->isEmpty())
                    <div class="empty-state">
                        <span class="empty-icon">📭</span>
                        <h4 class="empty-title">No notifications yet</h4>
                        <p class="empty-desc">You're all caught up! Follow commodities to receive price alerts.</p>
                        <a href="{{ route('follows.index') }}" class="btn-primary inline-flex">
                            Manage Follows
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($notifications as $notification)
                            @php
                                $data = $notification->data;
                                $type = $data['type'] ?? 'general';
                                
                                // Border color class based on type
                                $borderClass = match($type) {
                                    'price_drop' => 'notif-price-drop',
                                    'price_spike' => 'notif-price-spike',
                                    'new_listing' => 'notif-new-listing',
                                    'weekly_summary' => 'notif-weekly',
                                    default => '',
                                };
                                
                                // Icon based on type
                                $icon = match($type) {
                                    'price_drop' => '📉',
                                    'price_spike' => '📈',
                                    'new_listing' => '🌾',
                                    'weekly_summary' => '📊',
                                    default => '🔔',
                                };
                            @endphp
                            <div x-show="filter === 'all' || filter === '{{ $type }}'" x-transition
                                 class="bg-white rounded-[12px] p-5 flex items-start justify-between transition-all duration-200 {{ $borderClass }}
                                    {{ $notification->read_at 
                                        ? 'border border-stone opacity-60 shadow-sm' 
                                        : 'border-2 border-primary/30 shadow-md bg-primary-muted/5' }}">
                                
                                <div class="flex items-start gap-4 flex-1 min-w-0">
                                    <div class="text-2xl mt-0.5 shrink-0">{{ $icon }}</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-soil text-sm leading-tight mb-1.5 {{ !$notification->read_at ? 'text-soil' : 'text-bark' }}">
                                            {{ $data['message'] ?? 'Notification' }}
                                        </p>
                                        <div class="flex items-center gap-3">
                                            <p class="text-xs text-bark font-mono">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                            @if(!$notification->read_at)
                                                <span class="w-2 h-2 bg-primary rounded-full"></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-stone hover:text-red-500 text-lg ml-4 p-1.5 rounded-lg hover:bg-red-50 transition-all" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($notifications->hasPages())
                        <div class="mt-8">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
