<x-admin-layout header="Users">

    {{-- Search & Filter --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex gap-3">
            <x-text-input name="search" placeholder="Search by name or email..."
                class="flex-1" :value="request('search')" />
            <select name="filter"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md text-sm">
                <option value="">All Users</option>
                <option value="active"  {{ request('filter') === 'active'  ? 'selected' : '' }}>Active</option>
                <option value="banned"  {{ request('filter') === 'banned'  ? 'selected' : '' }}>Banned</option>
            </select>
            <x-primary-button>Filter</x-primary-button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-3 text-left">User</th>
                    <th class="px-6 py-3 text-left">County</th>
                    <th class="px-6 py-3 text-left">Phone</th>
                    <th class="px-6 py-3 text-center">Listings</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Joined</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                        <td class="px-6 py-3">
                            <p class="font-medium text-gray-800 dark:text-gray-100">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </td>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->county }}</td>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $user->phone }}</td>
                        <td class="px-6 py-3 text-center text-gray-600 dark:text-gray-300">
                            {{ $user->listings_count }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($user->is_banned)
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs">Banned</span>
                            @elseif($user->hasRole('admin'))
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-xs">Admin</span>
                            @else
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">Active</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center text-xs text-gray-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                    class="text-xs text-indigo-600 hover:underline">View</a>

                                @if(!$user->hasRole('admin'))
                                    @if($user->is_banned)
                                        <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="text-xs text-green-600 hover:underline">Unban</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="text-xs text-red-600 hover:underline">Ban</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $users->links() }}</div>
    </div>
</x-admin-layout>