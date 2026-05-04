<x-admin-layout header="User Details: {{ $user->name }}">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Profile Card --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-stone rounded-xl shadow-sm p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
                <div class="w-24 h-24 bg-parchment rounded-full mx-auto mb-4 flex items-center justify-center text-4xl border-2 border-stone">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h2 class="font-display text-2xl font-bold text-soil">{{ $user->name }}</h2>
                <p class="text-bark font-bold text-sm">{{ $user->email }}</p>
                
                <div class="mt-6 pt-6 border-t border-stone flex justify-center gap-4">
                    @if($user->is_banned)
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold uppercase tracking-wider">Banned</span>
                    @elseif($user->hasRole('admin'))
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold uppercase tracking-wider">Admin</span>
                    @else
                        <span class="px-3 py-1 bg-primary-muted text-primary-dark rounded-full text-xs font-bold uppercase tracking-wider">Active User</span>
                    @endif
                </div>

                <div class="mt-8 space-y-4">
                    @if(!$user->hasRole('admin'))
                        @if($user->is_banned)
                            <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-primary w-full justify-center bg-green-600 hover:bg-green-700">
                                    Unban User
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-primary w-full justify-center bg-red-600 hover:bg-red-700">
                                    Ban User
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>

            <div class="bg-white border border-stone rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="font-display text-lg font-bold text-soil border-b border-stone pb-2">Information</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-bark font-bold">County</span>
                        <span class="text-soil">{{ $user->county ?? 'Not specified' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-bark font-bold">Phone</span>
                        <span class="text-soil font-mono">{{ $user->phone ?? 'Not specified' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-bark font-bold">Joined</span>
                        <span class="text-soil">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($user->banned_at)
                        <div class="flex justify-between">
                            <span class="text-red-600 font-bold">Banned At</span>
                            <span class="text-red-700 font-mono">{{ $user->banned_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Listings --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-stone rounded-xl shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-stone flex items-center justify-between">
                    <h3 class="font-display text-xl font-bold text-soil">User Listings</h3>
                    <span class="bg-parchment text-soil px-3 py-1 rounded-full text-xs font-bold border border-stone">
                        {{ $user->listings->count() }} Total
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs font-bold text-bark uppercase bg-parchment/50 border-b border-stone">
                            <tr>
                                <th class="px-8 py-4">Title</th>
                                <th class="px-8 py-4">Commodity</th>
                                <th class="px-8 py-4 text-right">Price</th>
                                <th class="px-8 py-4 text-center">Status</th>
                                <th class="px-8 py-4 text-center">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone">
                            @forelse ($user->listings as $listing)
                                <tr class="hover:bg-parchment/30 transition-colors">
                                    <td class="px-8 py-4 font-bold text-soil">
                                        {{ Str::limit($listing->title, 40) }}
                                    </td>
                                    <td class="px-8 py-4 text-bark flex items-center gap-2">
                                        <span>{{ $listing->commodity->icon }}</span> {{ $listing->commodity->name }}
                                    </td>
                                    <td class="px-8 py-4 text-right font-mono font-bold text-soil">
                                        KES {{ number_format($listing->price_per_unit, 0) }}
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
                                        {{ $listing->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-bark font-bold">
                                        No listings found for this user.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
