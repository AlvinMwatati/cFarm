<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Marketplace') }}
            </h2>
            <a href="{{ route('listings.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                + Post a Listing
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Listings Grid --}}
            @if ($listings->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No listings yet.</p>
                    <a href="{{ route('listings.create') }}" class="mt-4 inline-block underline text-indigo-600 dark:text-indigo-400">
                        Be the first to post one
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($listings as $listing)
                        <a href="{{ route('listings.show', $listing) }}"
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition block">

                            {{-- Image --}}
                            @if ($listing->getMedia('images')->isNotEmpty())
                                <img src="{{ $listing->getFirstMediaUrl('images', 'thumb') }}"
                                    alt="{{ $listing->title }}"
                                    class="w-full h-48 object-cover" />
                            @else
                                <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-400 dark:text-gray-500 text-4xl">🌾</span>
                                </div>
                            @endif

                            {{-- Details --}}
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 px-2 py-0.5 rounded-md">
                                        {{ $listing->commodity->name }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $listing->county }}</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 dark:text-gray-100 mt-2">
                                    {{ $listing->title }}
                                </h3>
                                <p class="text-indigo-600 dark:text-indigo-400 font-bold mt-2">
                                    KES {{ number_format($listing->price_per_unit, 2) }}
                                    <span class="text-xs font-normal text-gray-500">/ {{ $listing->commodity->unit->value }}</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Min. order: {{ $listing->minimum_order_quantity }} {{ $listing->commodity->unit->value }}s
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $listings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
