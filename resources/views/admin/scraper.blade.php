<x-admin-layout header="KAMIS Scraper">

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach ([
            ['label' => 'Total Price Records',  'value' => number_format($totalPrices)],
            ['label' => 'Unique Commodities',    'value' => $uniqueCommodities],
            ['label' => 'Latest Price Date',     'value' => $latestPriceDate ?? 'Never'],
            ['label' => 'Last Run',              'value' => $lastRun?->created_at->diffForHumans() ?? 'Never'],
        ] as $stat)
            <div class="bg-white border border-stone rounded-xl shadow-sm p-6 relative overflow-hidden group">
                <p class="font-mono text-xs text-bark uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
                <p class="font-display text-2xl font-bold text-soil mt-1">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Live status banner --}}
    <div id="scraper-status-banner"
        class="mb-6 p-4 rounded-xl text-sm font-bold shadow-sm hidden border">
    </div>

    {{-- Trigger card --}}
    <div class="bg-white border border-stone rounded-xl shadow-sm p-8 mb-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="font-display text-xl font-bold text-soil flex items-center gap-2">Manual Scrape</h3>
                <p class="text-sm text-bark mt-2">
                    Runs in the background. You can navigate away — it will continue until complete.
                    Scheduled automatically daily at 6:00 AM.
                </p>
                <p id="scraper-inline-status" class="text-sm mt-2 text-primary font-bold"></p>
            </div>
            <form method="POST" action="{{ route('admin.scraper.run') }}" id="scraper-form" class="w-full sm:w-auto shrink-0">
                @csrf
                <button type="submit" id="scraper-btn"
                    class="btn-primary w-full justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                    🤖 Run Now
                </button>
            </form>
        </div>
    </div>

    {{-- Run history --}}
    <div class="bg-white border border-stone rounded-xl shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-stone">
            <h3 class="font-display text-xl font-bold text-soil flex items-center gap-2">Run History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-bark uppercase bg-parchment/50 border-b border-stone">
                    <tr>
                        <th class="px-8 py-4">Date</th>
                        <th class="px-8 py-4 text-center">Status</th>
                        <th class="px-8 py-4 text-center">Products</th>
                        <th class="px-8 py-4 text-center">Rows Saved</th>
                        <th class="px-8 py-4 text-center">Duration</th>
                        <th class="px-8 py-4 text-center">Errors</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-parchment/30 transition-colors">
                            <td class="px-8 py-4 text-bark font-bold">
                                {{ $log->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $log->success ? 'bg-primary-muted text-primary-dark' : 'bg-red-100 text-red-700' }}">
                                    {{ $log->success ? '✅ Success' : '❌ Failed' }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-center font-mono text-soil">
                                {{ number_format($log->products_scraped) }}
                            </td>
                            <td class="px-8 py-4 text-center font-mono text-soil">
                                {{ number_format($log->rows_saved) }}
                            </td>
                            <td class="px-8 py-4 text-center font-mono text-soil">
                                {{ $log->duration_seconds }}s
                            </td>
                            <td class="px-8 py-4 text-center">
                                @if(!empty($log->errors))
                                    <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">{{ count($log->errors) }} error(s)</span>
                                @else
                                    <span class="text-xs text-stone">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-bark font-bold">
                                No runs yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-4 border-t border-stone">{{ $logs->links() }}</div>
    </div>

    {{-- Live polling script --}}
    <script>
        const statusUrl   = "{{ route('admin.scraper.status') }}";
        const banner      = document.getElementById('scraper-status-banner');
        const inlineStatus = document.getElementById('scraper-inline-status');
        const btn         = document.getElementById('scraper-btn');
        let   pollInterval = null;

        function updateUI(data) {
            if (data.running) {
                // Show running state
                banner.className = 'mb-6 p-4 rounded-xl text-sm font-bold bg-primary-muted border border-primary/20 text-primary-dark flex items-center gap-2';
                banner.innerHTML = '<svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>' + (data.message || 'Scrape running...');
                banner.classList.remove('hidden');
                inlineStatus.textContent = '⏳ Running — started ' + (data.started_at ? new Date(data.started_at).toLocaleTimeString() : '');
                btn.disabled = true;
                btn.textContent = '⏳ Running...';
            } else if (data.message) {
                // Show result
                const success = data.success !== false;
                banner.className = 'mb-6 p-4 rounded-xl text-sm font-bold border ' +
                    (success
                        ? 'bg-primary-muted border-primary/20 text-primary-dark'
                        : 'bg-red-50 border-red-200 text-red-700');
                banner.textContent = data.message;
                banner.classList.remove('hidden');
                inlineStatus.textContent = '';
                btn.disabled = false;
                btn.textContent = '🤖 Run Now';

                // Reload the logs table after a successful run
                if (success && pollInterval) {
                    clearInterval(pollInterval);
                    pollInterval = null;
                    setTimeout(() => window.location.reload(), 2000);
                }
            } else {
                banner.classList.add('hidden');
                btn.disabled = false;
                btn.textContent = '🤖 Run Now';
            }
        }

        function pollStatus() {
            fetch(statusUrl)
                .then(r => r.json())
                .then(data => {
                    updateUI(data);
                    if (!data.running && pollInterval) {
                        clearInterval(pollInterval);
                        pollInterval = null;
                    }
                })
                .catch(() => {});
        }

        // Check status on page load
        pollStatus();

        // If running, poll every 5 seconds
        @if($scraperStatus['running'] ?? false)
            pollInterval = setInterval(pollStatus, 5000);
        @endif

        // Start polling when form is submitted
        document.getElementById('scraper-form').addEventListener('submit', function() {
            setTimeout(() => {
                pollInterval = setInterval(pollStatus, 5000);
            }, 1500);
        });
    </script>

</x-admin-layout>
