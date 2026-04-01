<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            My Followed Commodities
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($follows->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400">You are not following any commodities yet.</p>
                    <a href="{{ route('commodities.index') }}"
                        class="mt-4 inline-block underline text-indigo-600 dark:text-indigo-400">
                        Browse commodities
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3 text-left">Commodity</th>
                                <th class="px-6 py-3 text-center">Price Drop</th>
                                <th class="px-6 py-3 text-center">Price Spike</th>
                                <th class="px-6 py-3 text-center">New Listings</th>
                                <th class="px-6 py-3 text-center">Weekly Summary</th>
                                <th class="px-6 py-3 text-center">Threshold</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($follows as $follow)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-100">
                                        {{ $follow->commodity->name }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{ $follow->notify_price_drop ? '✅' : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{ $follow->notify_price_spike ? '✅' : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{ $follow->notify_new_listing ? '✅' : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{ $follow->notify_weekly_summary ? '✅' : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-500">
                                        {{ $follow->price_change_threshold }}%
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST"
                                            action="{{ route('commodities.unfollow', $follow->commodity) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-xs text-red-500 hover:underline">
                                                Unfollow
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
