@section('title', 'Commodity Directory — cFarm')
@section('meta_description', 'Browse all farm commodities tracked on cFarm. View prices, listings, and market data for grains, vegetables, fruits, and more.')

<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="font-display text-3xl sm:text-4xl font-bold text-soil">Commodity Directory</h1>
                    <p class="text-bark mt-1 text-lg">Browse all available commodities by category.</p>
                </div>
                @can('create', App\Models\Commodity::class)
                    <a href="{{ route('commodities.create') }}" class="btn-primary inline-flex items-center gap-2 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Commodity
                    </a>
                @endcan
            </div>

            {{-- Search --}}
            <div x-data="{ search: '' }" class="space-y-6">
                <div class="bg-white border border-stone rounded-[12px] p-4 shadow-sm">
                    <div class="relative">
                        <svg class="absolute left-3 top-3 w-5 h-5 text-bark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" x-model="search" placeholder="Search commodities... (e.g. maize, tomato, beans)" 
                               class="w-full pl-10 rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white">
                    </div>
                </div>

                {{-- Filter tabs --}}
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1" x-data="{ active: 'all' }">
                    <button @click="active = 'all'" :class="active === 'all' ? 'active' : ''" class="filter-tab">All</button>
                    @foreach ($commodities->keys() as $category)
                        <button @click="active = '{{ Str::slug($category) }}'" :class="active === '{{ Str::slug($category) }}' ? 'active' : ''" class="filter-tab">
                            @php
                                $catIcon = '📌';
                                if (stripos($category, 'cereal') !== false || stripos($category, 'grain') !== false) $catIcon = '🌾';
                                elseif (stripos($category, 'vegetable') !== false) $catIcon = '🥬';
                                elseif (stripos($category, 'fruit') !== false) $catIcon = '🍎';
                                elseif (stripos($category, 'legume') !== false || stripos($category, 'pulse') !== false) $catIcon = '🫘';
                                elseif (stripos($category, 'tuber') !== false || stripos($category, 'root') !== false) $catIcon = '🥔';
                                elseif (stripos($category, 'livestock') !== false || stripos($category, 'animal') !== false) $catIcon = '🐄';
                            @endphp
                            {{ $catIcon }} {{ $category }}
                        </button>
                    @endforeach
                </div>

                {{-- Categories --}}
                @forelse ($commodities as $category => $items)
                    <div x-show="active === 'all' || active === '{{ Str::slug($category) }}'" x-transition class="space-y-4">
                        <h3 class="font-display text-2xl font-bold text-soil flex items-center gap-2">
                            @php
                                $catIcon = '📌';
                                if (stripos($category, 'cereal') !== false || stripos($category, 'grain') !== false) $catIcon = '🌾';
                                elseif (stripos($category, 'vegetable') !== false) $catIcon = '🥬';
                                elseif (stripos($category, 'fruit') !== false) $catIcon = '🍎';
                                elseif (stripos($category, 'legume') !== false || stripos($category, 'pulse') !== false) $catIcon = '🫘';
                                elseif (stripos($category, 'tuber') !== false || stripos($category, 'root') !== false) $catIcon = '🥔';
                                elseif (stripos($category, 'livestock') !== false || stripos($category, 'animal') !== false) $catIcon = '🐄';
                            @endphp
                            <span>{{ $catIcon }}</span>
                            {{ $category }}
                            <span class="text-sm font-mono text-bark font-normal ml-1">({{ $items->count() }})</span>
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                            @foreach ($items as $commodity)
                                <div x-show="!search || '{{ strtolower($commodity->name) }}'.includes(search.toLowerCase())" x-transition>
                                    <a href="{{ route('commodities.show', $commodity) }}" class="group block h-full">
                                        <div class="bg-white border border-stone rounded-[12px] p-5 hover:border-primary transition-all duration-200 h-full flex flex-col justify-between shadow-sm hover:shadow-md">
                                            <div>
                                                <div class="flex items-center gap-3 mb-3">
                                                    <span class="text-3xl filter drop-shadow-sm">{{ $commodity->icon ?? '🌾' }}</span>
                                                    <div>
                                                        <h4 class="font-bold text-soil group-hover:text-primary transition-colors line-clamp-1">
                                                            {{ $commodity->name }}
                                                        </h4>
                                                        <p class="text-xs text-bark">per {{ $commodity->unit->value }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4 pt-3 border-t border-stone/30 flex items-center justify-between gap-2">
                                                <a href="{{ route('insights.show', $commodity) }}" class="text-xs font-bold text-primary hover:underline" @click.stop>
                                                    📈 Prices
                                                </a>
                                                <a href="{{ route('listings.index', ['commodity_id' => $commodity->id]) }}" class="text-xs font-bold text-secondary hover:underline" @click.stop>
                                                    📋 Listings
                                                </a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <span class="empty-icon">📭</span>
                        <h4 class="empty-title">No Commodities Found</h4>
                        <p class="empty-desc">There are currently no commodities listed in the directory.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
