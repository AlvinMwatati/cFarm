<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Listings') }}
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

            @if ($listings->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">You have no listings yet.</p>
                    <a href="{{ route('listings.create') }}" class="mt-4 inline-block underline text-indigo-600 dark:text-indigo-400">
                        Post your first listing
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 uppercase text-xs tracking-wide">
                            <tr>
                                <th class="px-6 py-3">Listing</th>
                                <th class="px-6 py-3">Commodity</th>
                                <th class="px-6 py-3">Price</th>
                                <th class="px-6 py-3">Quantity</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($listings as $listing)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-800 dark:text-gray-100">{{ $listing->title }}</p>
                                        <p class="text-xs text-gray-400">{{ $listing->county }}{{ $listing->town ? ', ' . $listing->town : '' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                        {{ $listing->commodity->name }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-100">
                                        KES {{ number_format($listing->price_per_unit, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                        {{ number_format($listing->quantity_available) }} {{ $listing->commodity->unit->value }}s
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $listing->status->value === 'active'
                                                ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                                : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                            {{ $listing->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('listings.show', $listing) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">
                                                View
                                            </a>
                                            <form method="POST" action="{{ route('listings.toggle-status', $listing) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="text-xs text-gray-500 dark:text-gray-400 hover:underline">
                                                    {{ $listing->status->value === 'active' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="px-6 py-4">
                        {{ $listings->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
