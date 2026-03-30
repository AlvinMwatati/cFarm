<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $commodity->name }} — Price Insights
            </h2>
            <a href="{{ route('insights.index') }}"
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                ← All Insights
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Price Range Card --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">
                    Current Price Range
                </h3>
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Lowest Price</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                            KES {{ number_format($price_range->min_price, 2) }}
                        </p>
                        <p class="text-xs text-gray-400">per {{ $price_range->unit }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Highest Price</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">
                            KES {{ number_format($price_range->max_price, 2) }}
                        </p>
                        <p class="text-xs text-gray-400">per {{ $price_range->unit }}</p>
                    </div>
                </div>
            </div>

            {{-- Weekly Trend --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">
                    Price Trend — Last 8 Weeks
                </h3>
                @if ($weekly_trend->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        Not enough data yet to show a trend.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 dark:text-gray-400 text-xs uppercase border-b border-gray-200 dark:border-gray-700">
                                    <th class="pb-2">Week of</th>
                                    <th class="pb-2 text-right">Avg Price (KES)</th>
                                    <th class="pb-2 text-right">Listings</th>
                                    <th class="pb-2 text-right">Change</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($weekly_trend as $i => $week)
                                    @php
                                        $prev   = $i > 0 ? $weekly_trend[$i - 1]->average_price : null;
                                        $change = $prev ? (($week->average_price - $prev) / $prev) * 100 : null;
                                    @endphp
                                    <tr>
                                        <td class="py-2 text-gray-700 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($week->week)->format('M d, Y') }}
                                        </td>
                                        <td class="py-2 text-right font-semibold text-gray-800 dark:text-gray-100">
                                            {{ number_format($week->average_price, 2) }}
                                        </td>
                                        <td class="py-2 text-right text-gray-500 dark:text-gray-400">
                                            {{ $week->listing_count }}
                                        </td>
                                        <td class="py-2 text-right">
                                            @if ($change === null)
                                                <span class="text-gray-400 text-xs">—</span>
                                            @elseif ($change > 0)
                                                <span class="text-red-500 text-xs">▲ {{ number_format(abs($change), 1) }}%</span>
                                            @elseif ($change < 0)
                                                <span class="text-green-500 text-xs">▼ {{ number_format(abs($change), 1) }}%</span>
                                            @else
                                                <span class="text-gray-400 text-xs">— 0%</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Average Price by County --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">
                    Average Price by County
                </h3>
                @if ($county_prices->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        No active listings for {{ $commodity->name }} yet.
                    </p>
                @else
                    <div class="space-y-3">
                        @php $max = $county_prices->max('average_price'); @endphp
                        @foreach ($county_prices as $row)
                            <div class="flex items-center gap-4">
                                <span class="text-sm text-gray-600 dark:text-gray-300 w-32 shrink-0">
                                    {{ $row->county }}
                                </span>
                                <div class="flex-1 bg-gray-100 dark:bg-gray-700 rounded-full h-3">
                                    <div class="bg-indigo-500 h-3 rounded-full"
                                        style="width: {{ ($row->average_price / $max) * 100 }}%">
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100 w-36 text-right">
                                    KES {{ number_format($row->average_price, 2) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Link to listings --}}
            <div class="text-center">
                <a href="{{ route('listings.index', ['commodity_id' => $commodity->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700
                           text-white text-sm font-semibold rounded-lg transition">
                    View all {{ $commodity->name }} listings
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
