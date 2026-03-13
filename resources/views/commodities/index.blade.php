<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Commodities') }}
            </h2>
            @can('create', App\Models\Commodity::class)
                <a href="{{ route('commodities.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                    + Add Commodity
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($commodities as $category => $items)
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">
                        {{ $category }}
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($items as $commodity)
                            <a href="{{ route('commodities.show', $commodity) }}"
                                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 hover:shadow-md transition block">
                                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $commodity->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">per {{ $commodity->unit->value }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
