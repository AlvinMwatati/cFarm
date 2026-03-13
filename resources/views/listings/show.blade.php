<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $listing->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Images --}}
            @if ($listing->getMedia('images')->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach ($listing->getMedia('images') as $image)
                        <img src="{{ $image->getUrl('thumb') }}" alt="{{ $listing->title }}"
                            class="w-full h-48 object-cover rounded-lg shadow-sm" />
                    @endforeach
                </div>
            @endif

            {{-- Main Details --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Commodity & Location --}}
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-2 py-1 rounded-md font-medium">
                        {{ $listing->commodity->name }}
                    </span>
                    <span>·</span>
                    <span>{{ $listing->county }}{{ $listing->town ? ', ' . $listing->town : '' }}</span>
                    <span>·</span>
                    <span class="{{ $listing->status->value === 'active' ? 'text-green-600' : 'text-gray-400' }} font-medium">
                        {{ $listing->status->label() }}
                    </span>
                </div>

                {{-- Description --}}
                @if ($listing->description)
                    <p class="text-gray-700 dark:text-gray-300 mb-6">{{ $listing->description }}</p>
                @endif

                {{-- Pricing & Quantity --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Price per {{ $listing->commodity->unit->value }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                            KES {{ number_format($listing->price_per_unit, 2) }}
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Available</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                            {{ number_format($listing->quantity_available) }}
                            <span class="text-sm font-normal">{{ $listing->commodity->unit->value }}s</span>
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Minimum Order</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                            {{ number_format($listing->minimum_order_quantity) }}
                            <span class="text-sm font-normal">{{ $listing->commodity->unit->value }}s</span>
                        </p>
                    </div>
                </div>

                {{-- Seller Contact --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">
                        Seller
                    </h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $listing->user->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $listing->user->county }}</p>
                        </div>
                        <a href="tel:{{ $listing->user->phone }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                            📞 {{ $listing->user->phone }}
                        </a>
                    </div>
                </div>

                {{-- Owner Controls --}}
                @can('update', $listing)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6 flex items-center gap-4">
                        <form method="POST" action="{{ route('listings.toggle-status', $listing) }}">
                            @csrf @method('PATCH')
                            <x-primary-button>
                                {{ $listing->status->value === 'active' ? 'Mark as Inactive' : 'Mark as Active' }}
                            </x-primary-button>
                        </form>
                    </div>
                @endcan

            </div>
        </div>
    </div>
</x-app-layout>
