<x-admin-layout header="KAMIS Scraper">

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach ([
            ['label' => 'Total Price Records',   'value' => number_format($totalPrices)],
            ['label' => 'Unique Commodities',     'value' => $uniqueCommodities],
            ['label' => 'Latest Price Date',      'value' => $latestPriceDate ?? 'Never'],
            ['label' => 'Last Run',               'value' => $lastRun?->created_at->diffForHumans() ?? 'Never'],
        ] as $stat)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $stat['label'] }}</p>
                <p class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Manual trigger --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-gray-100">Manual Scrape</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Trigger a fresh scrape of KAMIS market prices. Runs automatically daily at 6:00 AM.
                </p>
            </div>
            <form method="POST" action="{{ route('admin.scraper.run') }}">
                @csrf
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white
                           font-semibold text-sm rounded-lg transition">
                    🤖 Run Now
                </button>
            </form>
        </div>
    </div>

    {{-- Run history --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-800 dark:text-gray-100">Run History</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Products Found</th>
                    <th class="px-6 py-3 text-center">Rows Saved</th>
                    <th class="px-6 py-3 text-center">Duration</th>
                    <th class="px-6 py-3 text-center">Errors</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($logs as $log)
                    <tr>
                        <td class="px-6 py-3 text-gray-700 dark:text-gray-300">
                            {{ $log->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            <span class="{{ $log->success ? 'text-green-600' : 'text-red-600' }} font-medium">
                                {{ $log->success ? '✅ Success' : '❌ Failed' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center text-gray-600 dark:text-gray-300">
                            {{ number_format($log->products_found) }}
                        </td>
                        <td class="px-6 py-3 text-center text-gray-600 dark:text-gray-300">
                            {{ number_format($log->rows_saved) }}
                        </td>
                        <td class="px-6 py-3 text-center text-gray-600 dark:text-gray-300">
                            {{ $log->duration_seconds }}s
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if(!empty($log->errors))
                                <span class="text-xs text-red-500">
                                    {{ count($log->errors) }} error(s)
                                </span>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $logs->links() }}</div>
    </div>
</x-admin-layout>