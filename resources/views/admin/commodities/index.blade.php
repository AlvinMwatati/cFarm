<x-admin-layout header="Manage Commodities">

    <div class="mb-8 flex justify-end">
        <a href="{{ route('admin.commodities.create') }}" class="btn-primary">
            + New Commodity
        </a>
    </div>

    <div class="bg-white border border-stone rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-bark uppercase bg-parchment/50 border-b border-stone">
                    <tr>
                        <th class="px-8 py-4">Commodity</th>
                        <th class="px-8 py-4 text-center">Category</th>
                        <th class="px-8 py-4 text-center">Unit</th>
                        <th class="px-8 py-4 text-center">Active Listings</th>
                        <th class="px-8 py-4 text-center">Status</th>
                        <th class="px-8 py-4 rounded-tr-xl"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone">
                    @forelse ($commodities as $commodity)
                        <tr class="hover:bg-parchment/30 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl filter drop-shadow-sm">{{ $commodity->icon }}</span>
                                    <div>
                                        <p class="font-bold text-soil">{{ $commodity->name }}</p>
                                        <p class="text-xs text-bark font-mono uppercase tracking-widest">{{ $commodity->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span class="px-2.5 py-1 bg-stone/20 text-bark rounded-lg text-xs font-bold uppercase tracking-wider">
                                    {{ $commodity->category->value }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-center text-bark font-bold">
                                {{ $commodity->unit->value }}
                            </td>
                            <td class="px-8 py-4 text-center font-mono font-bold text-soil">
                                {{ $commodity->listings_count }}
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider
                                    {{ $commodity->is_active
                                        ? 'bg-primary-muted text-primary-dark'
                                        : 'bg-red-50 text-red-600 border border-red-100' }}">
                                    {{ $commodity->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <form method="POST" action="{{ route('admin.commodities.toggle', $commodity) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-sm font-bold {{ $commodity->is_active ? 'text-red-600 hover:text-red-800' : 'text-primary hover:text-primary-dark' }} transition-colors">
                                            {{ $commodity->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-bark font-bold">
                                No commodities found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-4 border-t border-stone">{{ $commodities->links() }}</div>
    </div>
</x-admin-layout>
