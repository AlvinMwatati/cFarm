<x-admin-layout header="Users">

    {{-- Search & Filter --}}
    <div class="bg-white border border-stone rounded-xl shadow-sm p-6 mb-8">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" placeholder="Search by name or email..."
                class="flex-1 w-full rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white" 
                value="{{ request('search') }}" />
            
            <select name="filter"
                class="w-full sm:w-48 rounded-lg border-stone shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white">
                <option value="">All Users</option>
                <option value="active"  {{ request('filter') === 'active'  ? 'selected' : '' }}>Active</option>
                <option value="banned"  {{ request('filter') === 'banned'  ? 'selected' : '' }}>Banned</option>
            </select>
            
            <button class="btn-primary shrink-0 justify-center">Filter</button>
        </form>
    </div>

    <div class="bg-white border border-stone rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-bark uppercase bg-parchment/50 border-b border-stone">
                    <tr>
                        <th class="px-8 py-4 rounded-tl-xl">User</th>
                        <th class="px-8 py-4">County</th>
                        <th class="px-8 py-4">Phone</th>
                        <th class="px-8 py-4 text-center">Listings</th>
                        <th class="px-8 py-4 text-center">Status</th>
                        <th class="px-8 py-4 text-center">Joined</th>
                        <th class="px-8 py-4 rounded-tr-xl"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone">
                    @foreach ($users as $user)
                        <tr class="hover:bg-parchment/30 transition-colors">
                            <td class="px-8 py-4">
                                <p class="font-bold text-soil">{{ $user->name }}</p>
                                <p class="text-xs text-bark mt-0.5">{{ $user->email }}</p>
                            </td>
                            <td class="px-8 py-4 text-bark">{{ $user->county }}</td>
                            <td class="px-8 py-4 text-bark font-mono">{{ $user->phone }}</td>
                            <td class="px-8 py-4 text-center text-soil font-bold font-mono">
                                {{ $user->listings_count }}
                            </td>
                            <td class="px-8 py-4 text-center">
                                @if($user->is_banned)
                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold uppercase tracking-wider">Banned</span>
                                @elseif($user->hasRole('admin'))
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-bold uppercase tracking-wider">Admin</span>
                                @else
                                    <span class="px-2.5 py-1 bg-primary-muted text-primary-dark rounded-lg text-xs font-bold uppercase tracking-wider">Active</span>
                                @endif
                            </td>
                            <td class="px-8 py-4 text-center text-xs text-bark font-bold">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-8 py-4 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">View</a>

                                    @if(!$user->hasRole('admin'))
                                        @if($user->is_banned)
                                            <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="text-sm font-bold text-green-600 hover:text-green-800 transition-colors">Unban</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="text-sm font-bold text-red-600 hover:text-red-800 transition-colors">Ban</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-8 py-4 border-t border-stone">{{ $users->links() }}</div>
    </div>
</x-admin-layout>