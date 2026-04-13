<x-admin-layout header="Listings">

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex gap-3">
            <x-text-input name="search" placeholder="Search listings..."
                class="flex-1" :value="request('search')" />
            <select name="status"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md text-sm">
                <option value="">All Statuses</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <x-primary-button>Filter</x-primary-button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-3 text-left">Listing</th>
                    <th class="px-6 py-3 text-left">Seller</th>
                    <th class="px-6 py-3 text-left">Commodity</th>
                    <th class="px-6 py-3 text-right">Price</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Posted</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($listings as $listing)
                    <tr>
                        <td class="px-6 py-3">
                            <a href="{{ route('listings.show', $listing) }}"
                                class="font-medium text-indigo-600 hover:underline">
                                {{ Str::limit($listing->title, 35) }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $listing->county }}</p>
                        </td>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                            {{ $listing->user->name }}
                            @if($listing->user->is_banned)
                                <span class="text-xs text-red-500 ml-1">(banned)</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                            {{ $listing->commodity->name }}
                        </td>
                        <td class="px-6 py-3 text-right font-medium text-gray-800 dark:text-gray-100">
                            KES {{ number_format($listing->price_per_unit, 0) }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $listing->status->value === 'active'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-500' }}">
                                {{ $listing->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center text-xs text-gray-400">
                            {{ $listing->created_at->format('M d') }}
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <form method="POST"
                                    action="{{ route('admin.listings.toggle', $listing) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-indigo-600 hover:underline">
                                        {{ $listing->status->value === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST"
                                    action="{{ route('admin.listings.destroy', $listing) }}"
                                    onsubmit="return confirm('Delete this listing?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $listings->links() }}</div>
    </div>
</x-admin-layout>