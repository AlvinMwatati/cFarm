<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $commodity->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center gap-3 mb-4">
                    <span
                        class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-3 py-1 rounded-md text-sm font-medium">
                        {{ $commodity->category->label() }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        Sold per {{ $commodity->unit->value }}
                    </span>
                </div>

                @if ($commodity->description)
                    <p class="text-gray-700 dark:text-gray-300">{{ $commodity->description }}</p>
                @endif

                <div class="mt-6">
                    <a href="{{ route('listings.index') }}?commodity={{ $commodity->id }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                        View all listings for {{ $commodity->name }}
                    </a>
                </div>

                {{-- Add this inside the commodity show view --}}
                <div class="mt-6 flex items-center gap-3">
                    <a href="{{ route('insights.show', $commodity) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700
               text-white text-sm font-semibold rounded-lg transition">
                        📊 View Price Insights
                    </a>
                    <a href="{{ route('listings.index', ['commodity_id' => $commodity->id]) }}"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border
               border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300
               text-sm font-semibold rounded-lg transition hover:bg-gray-50">
                        View all listings
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
