@section('title', ($listing->title ?? 'Listing') . ' — cFarm Marketplace')
@section('meta_description', 'Buy ' . ($listing->commodity->name ?? 'produce') . ' from ' . ($listing->user->name ?? 'a farmer') . ' in ' . ($listing->county->value ?? 'Kenya') . ' on cFarm.')

<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <div class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('listings.index') }}" class="text-primary font-bold hover:underline">Marketplace</a>
                <span class="text-stone">→</span>
                <span class="text-bark font-bold">{{ Str::limit($listing->title, 50) }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                {{-- LEFT: Main Content (65%) --}}
                <div class="lg:col-span-3 space-y-6">

                    {{-- Image Gallery --}}
                    @if ($listing->images && count($listing->images) > 0)
                        <div x-data="{ active: 0 }" class="space-y-3">
                            {{-- Main image --}}
                            <div class="bg-white border border-stone rounded-[12px] overflow-hidden">
                                @foreach ($listing->images as $i => $image)
                                    <img x-show="active === {{ $i }}"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         src="{{ asset('storage/' . $image) }}"
                                         alt="{{ $listing->title }} - Image {{ $i + 1 }}"
                                         class="gallery-main"
                                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                                @endforeach
                            </div>
                            {{-- Thumbnails --}}
                            @if (count($listing->images) > 1)
                                <div class="flex gap-2 overflow-x-auto no-scrollbar py-1">
                                    @foreach ($listing->images as $i => $image)
                                        <button @click="active = {{ $i }}" class="shrink-0">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 alt="Thumbnail {{ $i + 1 }}"
                                                 class="gallery-thumb"
                                                 :class="active === {{ $i }} ? 'active' : ''">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-parchment border border-stone rounded-[12px] aspect-video flex items-center justify-center">
                            <div class="text-center">
                                <span class="text-6xl block mb-2">{{ $listing->commodity->icon ?? '🌾' }}</span>
                                <p class="text-sm text-bark">No images uploaded</p>
                            </div>
                        </div>
                    @endif

                    {{-- Title & Status --}}
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider
                                {{ $listing->status->value === 'active'
                                    ? 'bg-secondary-muted text-secondary-dark'
                                    : 'bg-stone/30 text-bark' }}">
                                {{ $listing->status->label() }}
                            </span>
                            <span class="text-xs text-bark font-mono">
                                Listed {{ $listing->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <h1 class="font-display text-3xl sm:text-4xl font-bold text-soil leading-tight">
                            {{ $listing->title }}
                        </h1>
                    </div>

                    {{-- Description --}}
                    @if ($listing->description)
                        <div class="bg-white border border-stone rounded-[12px] p-6">
                            <h3 class="font-display text-lg font-bold text-soil mb-3 flex items-center gap-2">
                                <span>📝</span> Description
                            </h3>
                            <div class="text-bark leading-relaxed whitespace-pre-line">{{ $listing->description }}</div>
                        </div>
                    @endif

                    {{-- Specs Table --}}
                    <div class="bg-white border border-stone rounded-[12px] p-6">
                        <h3 class="font-display text-lg font-bold text-soil mb-4 flex items-center gap-2">
                            <span>📋</span> Details
                        </h3>
                        <div class="divide-y divide-stone/30">
                            <div class="flex justify-between py-3">
                                <span class="text-bark font-bold text-sm">Commodity</span>
                                <span class="text-soil font-bold flex items-center gap-1.5">
                                    <span>{{ $listing->commodity->icon ?? '🌾' }}</span>
                                    {{ $listing->commodity->name }}
                                </span>
                            </div>
                            <div class="flex justify-between py-3">
                                <span class="text-bark font-bold text-sm">Category</span>
                                <span class="text-soil">{{ $listing->commodity->category->label() }}</span>
                            </div>
                            <div class="flex justify-between py-3">
                                <span class="text-bark font-bold text-sm">Quantity</span>
                                <span class="text-soil font-bold font-mono">{{ number_format($listing->quantity) }} {{ $listing->commodity->unit->value }}</span>
                            </div>
                            <div class="flex justify-between py-3">
                                <span class="text-bark font-bold text-sm">Location</span>
                                <span class="text-soil font-bold">{{ $listing->county }}</span>
                            </div>
                            <div class="flex justify-between py-3">
                                <span class="text-bark font-bold text-sm">Price per {{ $listing->commodity->unit->value }}</span>
                                <span class="font-mono text-xl font-bold text-soil">
                                    <span class="text-xs text-bark font-sans font-normal mr-1">KES</span>{{ number_format($listing->price_per_unit, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- KAMIS Market Price Comparison --}}
                    @if (isset($kamisPrice) && $kamisPrice)
                        <div class="bg-secondary-muted/30 border border-secondary/20 rounded-[12px] p-6">
                            <h3 class="font-display text-lg font-bold text-soil mb-4 flex items-center gap-2">
                                <span>📊</span> KAMIS Market Reference
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center bg-white rounded-lg p-4 border border-stone/30">
                                    <p class="text-xs text-bark uppercase font-bold tracking-widest mb-1">This Listing</p>
                                    <p class="font-mono text-2xl font-bold text-soil">
                                        <span class="text-xs text-bark font-sans mr-1">KES</span>{{ number_format($listing->price_per_unit, 0) }}
                                    </p>
                                </div>
                                <div class="text-center bg-white rounded-lg p-4 border border-stone/30">
                                    <p class="text-xs text-bark uppercase font-bold tracking-widest mb-1">KAMIS Avg</p>
                                    <p class="font-mono text-2xl font-bold text-soil">
                                        <span class="text-xs text-bark font-sans mr-1">KES</span>{{ number_format($kamisPrice, 0) }}
                                    </p>
                                </div>
                            </div>
                            @php
                                $diff = $kamisPrice > 0 ? (($listing->price_per_unit - $kamisPrice) / $kamisPrice) * 100 : 0;
                            @endphp
                            <p class="mt-3 text-center text-sm font-bold {{ $diff <= 0 ? 'text-secondary' : 'text-primary' }}">
                                {{ $diff <= 0 ? '✅ ' . abs(number_format($diff, 1)) . '% below' : '⬆️ ' . number_format($diff, 1) . '% above' }} the national average
                            </p>
                        </div>
                    @endif
                </div>

                {{-- RIGHT: Sticky Contact Card (35%) --}}
                <div class="lg:col-span-2">
                    <div class="lg:sticky lg:top-24 space-y-6">

                        {{-- Price Highlight --}}
                        <div class="bg-white border-2 border-primary/20 rounded-[12px] p-6 text-center shadow-sm">
                            <p class="text-xs text-bark uppercase font-bold tracking-widest mb-2">Price per {{ $listing->commodity->unit->value }}</p>
                            <p class="font-mono text-4xl font-bold text-soil mb-1">
                                <span class="text-lg text-bark font-sans font-normal mr-1">KES</span>{{ number_format($listing->price_per_unit, 2) }}
                            </p>
                            <p class="text-sm text-bark">
                                Total value: <span class="font-mono font-bold text-soil">KES {{ number_format($listing->price_per_unit * $listing->quantity, 0) }}</span>
                                for {{ number_format($listing->quantity) }} {{ $listing->commodity->unit->value }}
                            </p>
                        </div>

                        {{-- Seller Contact Card --}}
                        <div class="bg-white border border-stone rounded-[12px] p-6 shadow-sm">
                            <h3 class="font-display text-lg font-bold text-soil mb-4 flex items-center gap-2">
                                <span>👤</span> Seller
                            </h3>

                            <div class="flex items-center gap-4 mb-5">
                                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-soil">{{ $listing->user->name }}</p>
                                    <p class="text-xs text-bark flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $listing->county }}
                                    </p>
                                </div>
                            </div>

                            @auth
                                <div class="space-y-3">
                                    {{-- Phone --}}
                                    @if ($listing->user->phone)
                                        <a href="tel:{{ $listing->user->phone }}" class="btn-primary w-full justify-center inline-flex items-center gap-2 py-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            Call {{ $listing->user->phone }}
                                        </a>
                                    @endif

                                    {{-- WhatsApp --}}
                                    @if ($listing->user->phone)
                                        @php
                                            $waNumber = preg_replace('/[^0-9]/', '', $listing->user->phone);
                                            if (str_starts_with($waNumber, '0')) $waNumber = '254' . substr($waNumber, 1);
                                            elseif (!str_starts_with($waNumber, '254')) $waNumber = '254' . $waNumber;
                                            $waMsg = urlencode("Hi! I'm interested in your listing \"" . $listing->title . "\" on cFarm. Is it still available?");
                                        @endphp
                                        <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsg }}" target="_blank" rel="noopener"
                                           class="w-full inline-flex items-center justify-center gap-2 py-3 bg-[#25D366] hover:bg-[#1DA851] text-white font-bold rounded-lg transition-all duration-200 text-sm">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.612.638l4.682-1.226A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.24 0-4.312-.726-5.993-1.957l-.42-.308-3.046.798.823-3.006-.337-.454A9.955 9.955 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                                            WhatsApp
                                        </a>
                                    @endif
                                </div>
                            @else
                                <div class="bg-parchment rounded-lg p-4 text-center border border-stone/50">
                                    <p class="text-sm text-bark mb-3">Log in to see seller contact details.</p>
                                    <a href="{{ route('login') }}" class="btn-primary inline-flex text-sm px-6">Log In</a>
                                </div>
                            @endauth
                        </div>

                        {{-- Safety Note --}}
                        <div class="bg-accent-light/30 border border-accent/20 rounded-[12px] p-4">
                            <p class="text-xs text-bark leading-relaxed">
                                <span class="font-bold text-soil">🛡️ Safety tip:</span> Always meet sellers in public places. Inspect produce before payment. Report suspicious listings.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Related Listings --}}
            @if (isset($relatedListings) && $relatedListings->isNotEmpty())
                <div class="mt-12 space-y-6">
                    <h2 class="font-display text-2xl font-bold text-soil flex items-center gap-2">
                        <span>🔗</span> More {{ $listing->commodity->name }} Listings
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($relatedListings->take(3) as $related)
                            <a href="{{ route('listings.show', $related) }}" class="group block">
                                <div class="bg-white border border-stone rounded-[12px] p-5 hover:border-primary transition-all duration-200 h-full flex flex-col justify-between shadow-sm hover:shadow-md">
                                    <div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="text-xl">{{ $related->commodity->icon ?? '🌾' }}</span>
                                            <h4 class="font-bold text-soil group-hover:text-primary transition-colors line-clamp-1">{{ $related->title }}</h4>
                                        </div>
                                        <p class="text-xs text-bark mb-2 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $related->county }}
                                        </p>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-stone/30 flex justify-between items-center">
                                        <span class="font-mono font-bold text-soil">
                                            <span class="text-xs text-bark font-sans font-normal mr-1">KES</span>{{ number_format($related->price_per_unit, 0) }}
                                        </span>
                                        <span class="text-primary text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">View →</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
