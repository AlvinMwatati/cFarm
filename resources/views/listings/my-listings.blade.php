@section('title', 'My Listings — cFarm')

<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-stone pb-6">
                <div>
                    <h1 class="font-display text-3xl font-bold text-soil">My Listings</h1>
                    <p class="text-bark mt-1">Manage your active and inactive marketplace listings.</p>
                </div>
                <a href="{{ route('listings.create') }}" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Post a Listing
                </a>
            </div>

            @if ($listings->isEmpty())
                <div class="empty-state">
                    <span class="empty-icon">📋</span>
                    <h4 class="empty-title">You have no listings yet</h4>
                    <p class="empty-desc">Start selling your commodities by posting your first listing.</p>
                    <a href="{{ route('listings.create') }}" class="btn-primary inline-flex">
                        Post your first listing
                    </a>
                </div>
            @else
                <div class="bg-white border border-stone shadow-sm rounded-[12px] overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-stone/20 text-bark uppercase text-xs tracking-wider font-bold border-b border-stone">
                                <tr>
                                    <th class="px-6 py-4">Listing</th>
                                    <th class="px-6 py-4">Commodity</th>
                                    <th class="px-6 py-4">Price (KES)</th>
                                    <th class="px-6 py-4">Quantity</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone">
                                @foreach ($listings as $listing)
                                    <tr class="hover:bg-stone/10 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-soil text-base">{{ $listing->title }}</p>
                                            <p class="text-xs text-bark font-mono mt-1">📍 {{ $listing->county }}{{ $listing->town ? ', ' . $listing->town : '' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="bg-primary-muted text-primary-dark px-2 py-1 rounded font-bold text-xs">
                                                {{ $listing->commodity->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-display font-bold text-primary text-lg">
                                            {{ number_format($listing->price_per_unit, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-soil font-bold">
                                            {{ number_format($listing->quantity_available) }} <span class="text-bark text-xs font-mono font-normal">{{ $listing->commodity->unit->value }}s</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold border
                                                {{ $listing->status->value === 'active'
                                                    ? 'bg-accent/10 text-accent-dark border-accent/20'
                                                    : 'bg-stone/20 text-bark border-stone' }}">
                                                {{ $listing->status->label() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('listings.show', $listing) }}"
                                                    class="text-primary hover:text-primary-dark font-bold text-xs uppercase tracking-wider transition-colors">
                                                    View
                                                </a>
                                                <form method="POST" action="{{ route('listings.toggle-status', $listing) }}">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="text-xs uppercase tracking-wider font-bold transition-colors
                                                        {{ $listing->status->value === 'active' ? 'text-bark hover:text-soil' : 'text-accent hover:text-accent-dark' }}">
                                                        {{ $listing->status->value === 'active' ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($listings->hasPages())
                        <div class="px-6 py-4 border-t border-stone bg-stone/5">
                            {{ $listings->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
