<x-admin-layout header="Listings">

    <div class="bg-white border border-stone rounded-xl shadow-sm p-6 mb-8">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" placeholder="Search listings..."
                class="flex-1 w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" 
                value="{{ request('search') }}" />
                
            <select name="status"
                class="w-full sm:w-48 rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white">
                <option value="">All Statuses</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            
            <button class="btn-primary shrink-0 justify-center">Filter</button>
        </form>
    </div>

    <div class="bg-white border border-stone rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-bark uppercase bg-parchment/50 border-b border-stone">
                    <tr>
                        <th class="px-8 py-4 rounded-tl-xl">Listing</th>
                        <th class="px-8 py-4">Seller</th>
                        <th class="px-8 py-4">Commodity</th>
                        <th class="px-8 py-4 text-right">Price</th>
                        <th class="px-8 py-4 text-center">Status</th>
                        <th class="px-8 py-4 text-center">Posted</th>
                        <th class="px-8 py-4 rounded-tr-xl"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone">
                    @foreach ($listings as $listing)
                        <tr class="hover:bg-parchment/30 transition-colors">
                            <td class="px-8 py-4">
                                <a href="{{ route('listings.show', $listing) }}"
                                    class="font-bold text-soil hover:text-primary transition-colors">
                                    {{ Str::limit($listing->title, 35) }}
                                </a>
                                <p class="text-xs text-bark mt-0.5">{{ $listing->county }}</p>
                            </td>
                            <td class="px-8 py-4 text-bark">
                                {{ $listing->user->name }}
                                @if($listing->user->is_banned)
                                    <span class="text-xs font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded-full ml-1 uppercase tracking-wide">Banned</span>
                                @endif
                            </td>
                            <td class="px-8 py-4 text-bark font-bold flex items-center gap-2">
                                <span>{{ $listing->commodity->icon }}</span> {{ $listing->commodity->name }}
                            </td>
                            <td class="px-8 py-4 text-right font-mono font-bold text-soil">
                                <span class="text-xs text-bark font-sans font-normal mr-1">KES</span>{{ number_format($listing->price_per_unit, 0) }}
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider
                                    {{ $listing->status->value === 'active'
                                        ? 'bg-primary-muted text-primary-dark'
                                        : 'bg-stone/30 text-bark' }}">
                                    {{ $listing->status->label() }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-center text-xs text-bark font-bold">
                                {{ $listing->created_at->format('M d') }}
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <form method="POST"
                                        action="{{ route('admin.listings.toggle', $listing) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">
                                            {{ $listing->status->value === 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route('admin.listings.destroy', $listing) }}"
                                        onsubmit="return confirm('Delete this listing?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-sm font-bold text-red-600 hover:text-red-800 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-8 py-4 border-t border-stone">{{ $listings->links() }}</div>
    </div>
</x-admin-layout>