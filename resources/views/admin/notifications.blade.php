<x-admin-layout header="Price Alerts Log">

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @foreach ([
            ['label' => 'Total Sent',    'value' => $stats['total'],        'icon' => '🔔'],
            ['label' => 'Unread',        'value' => $stats['unread'],       'icon' => '📬'],
            ['label' => 'Price Drops',   'value' => $stats['price_drops'],  'icon' => '📉'],
            ['label' => 'Price Spikes',  'value' => $stats['price_spikes'], 'icon' => '📈'],
            ['label' => 'New Listings',  'value' => $stats['new_listings'], 'icon' => '🌾'],
            ['label' => 'Weekly Reports','value' => $stats['weekly'],       'icon' => '📊'],
        ] as $stat)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 flex items-center gap-3">
                <span class="text-2xl">{{ $stat['icon'] }}</span>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Notifications table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-800 dark:text-gray-100">Recent Alerts</h3>
        </div>

        @if ($notifications->isEmpty())
            <div class="p-12 text-center text-gray-400">
                No notifications sent yet.
            </div>
        @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-3 text-left">Type</th>
                        <th class="px-6 py-3 text-left">Message</th>
                        <th class="px-6 py-3 text-left">Recipient</th>
                        <th class="px-6 py-3 text-center">Read</th>
                        <th class="px-6 py-3 text-center">Sent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($notifications as $notification)
                        @php $data = $notification->data; @endphp
                        <tr>
                            <td class="px-6 py-3">
                                <span class="text-lg">
                                    @if(($data['type'] ?? '') === 'price_drop')    📉
                                    @elseif(($data['type'] ?? '') === 'price_spike')  📈
                                    @elseif(($data['type'] ?? '') === 'new_listing')  🌾
                                    @elseif(($data['type'] ?? '') === 'weekly_summary') 📊
                                    @else 🔔
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500 ml-1">
                                    {{ str_replace('_', ' ', $data['type'] ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-700 dark:text-gray-300">
                                {{ $data['message'] ?? '—' }}
                            </td>
                            <td class="px-6 py-3 text-gray-500">
                                {{ $notification->notifiable?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if($notification->read_at)
                                    <span class="text-green-500 text-xs">✓ Read</span>
                                @else
                                    <span class="text-gray-400 text-xs">Unread</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-center text-xs text-gray-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">{{ $notifications->links() }}</div>
        @endif
    </div>
</x-admin-layout>