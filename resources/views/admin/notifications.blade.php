<x-admin-layout header="Price Alerts Log">

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        @foreach ([
            ['label' => 'Total Sent',    'value' => $stats['total'],        'icon' => '🔔'],
            ['label' => 'Unread',        'value' => $stats['unread'],       'icon' => '📬'],
            ['label' => 'Price Drops',   'value' => $stats['price_drops'],  'icon' => '📉'],
            ['label' => 'Price Spikes',  'value' => $stats['price_spikes'], 'icon' => '📈'],
            ['label' => 'New Listings',  'value' => $stats['new_listings'], 'icon' => '🌾'],
            ['label' => 'Weekly Reports','value' => $stats['weekly'],       'icon' => '📊'],
        ] as $stat)
            <div class="bg-white border border-stone rounded-xl shadow-sm p-6 flex items-center gap-4 relative overflow-hidden group">
                <div class="absolute -right-2 -bottom-2 text-4xl opacity-5 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                    {{ $stat['icon'] }}
                </div>
                <span class="text-3xl filter drop-shadow-sm">{{ $stat['icon'] }}</span>
                <div>
                    <p class="font-mono text-xs text-bark uppercase tracking-widest mb-0.5">{{ $stat['label'] }}</p>
                    <p class="font-display text-2xl font-bold text-soil">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Notifications table --}}
    <div class="bg-white border border-stone rounded-xl shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-stone">
            <h3 class="font-display text-xl font-bold text-soil flex items-center gap-2">Recent Alerts</h3>
        </div>

        @if ($notifications->isEmpty())
            <div class="p-16 text-center text-bark font-bold">
                No notifications sent yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs font-bold text-bark uppercase bg-parchment/50 border-b border-stone">
                        <tr>
                            <th class="px-8 py-4">Type</th>
                            <th class="px-8 py-4">Message</th>
                            <th class="px-8 py-4">Recipient</th>
                            <th class="px-8 py-4 text-center">Read</th>
                            <th class="px-8 py-4 text-center">Sent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone">
                        @foreach ($notifications as $notification)
                            @php $data = $notification->data; @endphp
                            <tr class="hover:bg-parchment/30 transition-colors">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg">
                                            @if(($data['type'] ?? '') === 'price_drop')    📉
                                            @elseif(($data['type'] ?? '') === 'price_spike')  📈
                                            @elseif(($data['type'] ?? '') === 'new_listing')  🌾
                                            @elseif(($data['type'] ?? '') === 'weekly_summary') 📊
                                            @else 🔔
                                            @endif
                                        </span>
                                        <span class="text-xs font-bold text-bark uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $data['type'] ?? 'unknown') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-soil font-bold">
                                    {{ $data['message'] ?? '—' }}
                                </td>
                                <td class="px-8 py-4 text-bark">
                                    {{ $notification->notifiable?->name ?? '—' }}
                                </td>
                                <td class="px-8 py-4 text-center">
                                    @if($notification->read_at)
                                        <span class="px-2 py-0.5 bg-primary-muted text-primary-dark rounded-lg text-xs font-bold uppercase tracking-wider">Read</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-stone/30 text-bark rounded-lg text-xs font-bold uppercase tracking-wider">Unread</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4 text-center text-xs text-bark font-bold">
                                    {{ $notification->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-8 py-4 border-t border-stone">{{ $notifications->links() }}</div>
        @endif
    </div>
</x-admin-layout>