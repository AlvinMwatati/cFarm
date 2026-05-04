<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-stone pb-6">
                <div>
                    <h2 class="font-display text-3xl font-bold text-soil">Marketplace</h2>
                    <p class="text-bark mt-1">
                        Find and trade agricultural commodities across Kenya.
                    </p>
                </div>
                <a href="{{ route('listings.create') }}" class="btn-primary">
                    + Post a Listing
                </a>
            </div>

            @if (session('success'))
                <div class="p-4 bg-accent/20 border border-accent text-soil rounded-[12px] font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8">
                
                {{-- Sidebar Filters --}}
                <aside class="w-full lg:w-1/4">
                    <div class="bg-white border border-stone shadow-sm rounded-[12px] p-6 sticky top-24">
                        <h3 class="font-display font-bold text-lg text-soil border-b border-stone pb-3 mb-4">Filters</h3>
                        
                        <form method="GET" action="{{ route('listings.index') }}" class="space-y-5">
                            
                            {{-- Search --}}
                            <div>
                                <label for="search" class="block text-sm font-bold text-soil mb-1">Search</label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search listings..." class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20">
                            </div>

                            {{-- Category --}}
                            <div>
                                <label for="category" class="block text-sm font-bold text-soil mb-1">Category</label>
                                <select id="category" name="category" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->value }}" {{ request('category') === $category->value ? 'selected' : '' }}>
                                            {{ $category->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Commodity --}}
                            <div>
                                <label for="commodity_id" class="block text-sm font-bold text-soil mb-1">Commodity</label>
                                <select id="commodity_id" name="commodity_id" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20">
                                    <option value="">All Commodities</option>
                                    @foreach ($commodities as $commodity)
                                        <option value="{{ $commodity->id }}" {{ request('commodity_id') == $commodity->id ? 'selected' : '' }}>
                                            {{ $commodity->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- County --}}
                            <div>
                                <label for="county" class="block text-sm font-bold text-soil mb-1">County</label>
                                <select id="county" name="county" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20">
                                    <option value="">All Counties</option>
                                    @foreach ($counties as $county)
                                        <option value="{{ $county->value }}" {{ request('county') === $county->value ? 'selected' : '' }}>
                                            {{ $county->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Sort --}}
                            <div>
                                <label for="sort" class="block text-sm font-bold text-soil mb-1">Sort By</label>
                                <select id="sort" name="sort" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20">
                                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>

                            {{-- Price Range --}}
                            <div>
                                <label class="block text-sm font-bold text-soil mb-2">Price Range (KES)</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 text-sm">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 text-sm">
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="pt-4 border-t border-stone flex flex-col gap-2">
                                <button type="submit" class="btn-primary w-full justify-center">
                                    Apply Filters
                                </button>
                                @if (request()->hasAny(['search', 'commodity_id', 'category', 'county', 'min_price', 'max_price', 'sort']))
                                    <a href="{{ route('listings.index') }}" class="btn-secondary w-full justify-center text-center">
                                        Clear Filters
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </aside>

                {{-- Listings Main Content --}}
                <main class="w-full lg:w-3/4">
                    <div class="mb-4 text-sm font-bold text-bark">
                        Showing {{ $listings->total() }} {{ Str::plural('listing', $listings->total()) }}
                    </div>

                    @if ($listings->isEmpty())
                        <div class="border-2 border-dashed border-stone rounded-[12px] p-16 text-center bg-white/50">
                            <div class="text-6xl mb-4">🔍</div>
                            <h3 class="font-display font-bold text-2xl text-soil mb-2">No Listings Found</h3>
                            <p class="text-bark mb-6">We couldn't find any listings matching your current filters.</p>
                            <a href="{{ route('listings.index') }}" class="btn-secondary inline-block">
                                Clear All Filters
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($listings as $listing)
                                <a href="{{ route('listings.show', $listing) }}" class="group block h-full">
                                    <div class="bg-white border border-stone rounded-[12px] overflow-hidden hover:border-primary hover:shadow-md transition-all h-full flex flex-col">
                                        
                                        {{-- Image Container --}}
                                        <div class="relative h-48 bg-stone/20 overflow-hidden">
                                            @if ($listing->getMedia('images')->isNotEmpty())
                                                <img src="{{ $listing->getFirstMediaUrl('images', 'thumb') }}" alt="{{ $listing->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-bark/50">
                                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span class="text-sm font-medium">No Image Provided</span>
                                                </div>
                                            @endif
                                            
                                            {{-- Badges --}}
                                            <div class="absolute top-3 left-3 flex flex-col gap-2">
                                                <span class="bg-white/90 backdrop-blur-sm text-soil text-xs font-bold px-2 py-1 rounded shadow-sm">
                                                    {{ $listing->commodity->name }}
                                                </span>
                                            </div>
                                            <div class="absolute top-3 right-3">
                                                <span class="bg-soil/90 backdrop-blur-sm text-white text-xs font-bold px-2 py-1 rounded shadow-sm flex items-center gap-1">
                                                    📍 {{ $listing->county }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Details --}}
                                        <div class="p-5 flex flex-col flex-grow">
                                            <h3 class="font-display font-bold text-lg text-soil mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                                                {{ $listing->title }}
                                            </h3>
                                            
                                            <div class="mt-auto pt-4 flex items-end justify-between border-t border-stone/50">
                                                <div>
                                                    <p class="text-xs text-bark mb-1 uppercase tracking-wider font-bold">Price</p>
                                                    <p class="font-display font-bold text-xl text-primary">
                                                        KES {{ number_format($listing->price_per_unit, 2) }}<span class="text-sm text-bark font-mono">/{{ $listing->commodity->unit->value }}</span>
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xs text-bark mb-1 uppercase tracking-wider font-bold">Min. Order</p>
                                                    <p class="font-bold text-soil text-sm">
                                                        {{ $listing->minimum_order_quantity }} {{ $listing->commodity->unit->value }}s
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-8">
                            {{ $listings->links() }}
                        </div>
                    @endif
                </main>
            </div>

        </div>
    </div>
</x-app-layout>
