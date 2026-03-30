<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Market Insights') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Most Listed Commodities --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    Most Active Commodities
                </h3>

                @if ($most_listed->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No listings yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($most_listed as $index => $item)
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-bold text-gray-400 w-6">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-1 bg-gray-100 dark:bg-gray-700 rounded-full h-4 overflow-hidden">
                                    <div class="bg-indigo-500 h-4 rounded-full"
                                        style="width: {{ ($item->listing_count / $most_listed->first()->listing_count) * 100 }}%">
                                    </div>
                                </div>
                                <a href="{{ route('insights.show', $item->commodity) }}"
                                    class="text-sm font-medium text-gray-800 dark:text-gray-100 hover:text-indigo-600 w-36">
                                    {{ $item->commodity->name }}
                                </a>
                                <span class="text-sm text-gray-500 dark:text-gray-400 w-24 text-right">
                                    {{ $item->listing_count }} {{ Str::plural('listing', $item->listing_count) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Browse by Commodity --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    Browse Price Insights by Commodity
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach ($commodities as $commodity)
                        <a href="{{ route('insights.show', $commodity) }}"
                            class="bg-gray-50 dark:bg-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900
                                   rounded-lg p-3 text-center transition block">
                            <p class="font-medium text-gray-800 dark:text-gray-100 text-sm">
                                {{ $commodity->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                per {{ $commodity->unit->value }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
