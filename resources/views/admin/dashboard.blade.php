<x-admin-layout header="Dashboard">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach ([
            ['label' => 'Total Users',      'value' => $stats['total_users'],       'icon' => '👥', 'color' => 'indigo'],
            ['label' => 'Active Listings',  'value' => $stats['active_listings'],   'icon' => '📋', 'color' => 'green'],
            ['label' => 'Commodities',      'value' => $stats['total_commodities'], 'icon' => '🌽', 'color' => 'yellow'],
            ['label' => 'Price Records',    'value' => number_format($stats['total_prices']), 'icon' => '📈', 'color' => 'purple'],
        ] as $stat)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ $stat['label'] }}
                        </p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                            {{ $stat['value'] }}
                        </p>
                    </div>
                    <span class="text-3xl">{{ $stat['icon'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- KAMIS Scraper Status --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-gray-100">🤖 KAMIS Scraper</h3>
                <a href="{{ route('admin.scraper') }}"
                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                    View logs →
                </a>
            </div>
            @if ($lastScrape)
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Last run</span>
                        <span class="font-medium text-gray-800 dark:text-gray-100">
                            {{ $lastScrape->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="{{ $lastScrape->success ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ $lastScrape->success ? '✅ Success' : '❌ Failed' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Rows saved</span>
                        <span class="font-medium text-gray-800 dark:text-gray-100">
                            {{ number_format($lastScrape->rows_saved) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Duration</span>
                        <span class="font-medium text-gray-800 dark:text-gray-100">
                            {{ $lastScrape->duration_seconds }}s
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.scraper.run') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white
                               text-sm font-semibold rounded-lg transition">
                        Run Scraper Now
                    </button>
                </form>
            @else
                <p class="text-sm text-gray-400">No scrape runs yet.</p>
                <form method="POST" action="{{ route('admin.scraper.run') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white
                               text-sm font-semibold rounded-lg transition">
                        Run First Scrape
                    </button>
                </form>
            @endif
        </div>

        {{-- User Stats --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-gray-100">👥 Users</h3>
                <a href="{{ route('admin.users') }}"
                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                    Manage →
                </a>
            </div>
            <div class="space-y-3">
                @foreach ($recentUsers as $user)
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-100">
                                {{ $user->name }}
                                @if($user->is_banned)
                                    <span class="text-xs bg-red-100 text-red-600 px-1.5 rounded ml-1">Banned</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-400">{{ $user->county }} · {{ $user->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('admin.users.show', $user) }}"
                            class="text-xs text-indigo-600 hover:underline">View</a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Listings --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-gray-100">📋 Recent Listings</h3>
                <a href="{{ route('admin.listings') }}"
                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                    View all →
                </a>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 uppercase border-b border-gray-200 dark:border-gray-700">
                        <th class="pb-2">Title</th>
                        <th class="pb-2">Seller</th>
                        <th class="pb-2">Commodity</th>
                        <th class="pb-2">Price</th>
                        <th class="pb-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($recentListings as $listing)
                        <tr>
                            <td class="py-2 text-gray-800 dark:text-gray-100">{{ Str::limit($listing->title, 30) }}</td>
                            <td class="py-2 text-gray-500">{{ $listing->user->name }}</td>
                            <td class="py-2 text-gray-500">{{ $listing->commodity->name }}</td>
                            <td class="py-2 font-medium text-gray-800 dark:text-gray-100">
                                KES {{ number_format($listing->price_per_unit, 0) }}
                            </td>
                            <td class="py-2">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $listing->status->value === 'active'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-100 text-gray-500' }}">
                                    {{ $listing->status->label() }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-admin-layout>