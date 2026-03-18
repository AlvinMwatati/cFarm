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

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('listings.index') }}">

                    {{-- Search --}}
                    <div class="mb-4">
                        <x-input-label for="search" :value="__('Search')" />
                        <x-text-input id="search" name="search" type="text" class="block mt-1 w-full"
                            placeholder="Search listings..." :value="request('search')" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                        {{-- Category --}}
                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <x-select-input id="category" name="category" class="block mt-1 w-full">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->value }}"
                                        {{ request('category') === $category->value ? 'selected' : '' }}>
                                        {{ $category->label() }}
                                    </option>
                                @endforeach
                            </x-select-input>
                        </div>

                        {{-- Commodity --}}
                        <div>
                            <x-input-label for="commodity_id" :value="__('Commodity')" />
                            <x-select-input id="commodity_id" name="commodity_id" class="block mt-1 w-full">
                                <option value="">All Commodities</option>
                                @foreach ($commodities as $commodity)
                                    <option value="{{ $commodity->id }}"
                                        {{ request('commodity_id') == $commodity->id ? 'selected' : '' }}>
                                        {{ $commodity->name }}
                                    </option>
                                @endforeach
                            </x-select-input>
                        </div>

                        {{-- County --}}
                        <div>
                            <x-input-label for="county" :value="__('County')" />
                            <x-select-input id="county" name="county" class="block mt-1 w-full">
                                <option value="">All Counties</option>
                                @foreach ($counties as $county)
                                    <option value="{{ $county->value }}"
                                        {{ request('county') === $county->value ? 'selected' : '' }}>
                                        {{ $county->value }}
                                    </option>
                                @endforeach
                            </x-select-input>
                        </div>

                        {{-- Sort --}}
                        <div>
                            <x-input-label for="sort" :value="__('Sort By')" />
                            <x-select-input id="sort" name="sort" class="block mt-1 w-full">
                                <option value="newest"     {{ request('sort', 'newest') === 'newest'     ? 'selected' : '' }}>Newest First</option>
                                <option value="price_asc"  {{ request('sort') === 'price_asc'            ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc'           ? 'selected' : '' }}>Price: High to Low</option>
                            </x-select-input>
                        </div>

                    </div>

                    {{-- Price Range --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="min_price" :value="__('Min Price (KES)')" />
                            <x-text-input id="min_price" name="min_price" type="number" class="block mt-1 w-full"
                                placeholder="0" :value="request('min_price')" min="0" step="0.01" />
                        </div>
                        <div>
                            <x-input-label for="max_price" :value="__('Max Price (KES)')" />
                            <x-text-input id="max_price" name="max_price" type="number" class="block mt-1 w-full"
                                placeholder="Any" :value="request('max_price')" min="0" step="0.01" />
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3">
                        <x-primary-button>
                            {{ __('Apply Filters') }}
                        </x-primary-button>

                        @if (request()->hasAny(['search', 'commodity_id', 'category', 'county', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('listings.index') }}"
                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                Clear Filters
                            </a>
                        @endif
                    </div>

                </form>
            </div>

            {{-- Results count --}}
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                {{ $listings->total() }} {{ Str::plural('listing', $listings->total()) }} found
            </p>

            {{-- Listings Grid --}}
            @if ($listings->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No listings match your filters.</p>
                    <a href="{{ route('listings.index') }}"
                        class="mt-4 inline-block underline text-indigo-600 dark:text-indigo-400">
                        Clear filters
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
