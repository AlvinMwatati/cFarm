<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Back Link --}}
            <div>
                <a href="{{ route('insights.index') }}" class="text-primary font-bold hover:underline">
                    ← Back to Market Insights
                </a>
            </div>

            {{-- Commodity Detail Header --}}
            <div class="bg-parchment border border-stone rounded-[12px] p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    @php
                        $icon = $commodity->icon ?? '🌾';
                    @endphp
                    <div class="flex items-center gap-4 mb-2">
                        <span class="text-5xl filter drop-shadow-sm">{{ $icon }}</span>
                        <h2 class="font-display text-4xl sm:text-5xl font-black text-soil">{{ $commodity->name }}</h2>
                    </div>
                    <p class="text-bark">Official Market Data • All Counties Average</p>
                </div>
                
                <div class="text-right">
                    <div class="text-sm text-bark uppercase tracking-wide font-bold mb-1">Current Average Price</div>
                    <div class="font-mono text-4xl sm:text-5xl font-medium text-soil">
                        @if ($price_range && $price_range->min_price && $price_range->max_price)
                            <span class="text-2xl text-bark font-sans font-normal mr-1">KES</span>{{ number_format(($price_range->min_price + $price_range->max_price) / 2, 0) }}
                        @else
                            KES 0
                        @endif
                        <span class="text-lg text-bark font-sans">/{{ $commodity->unit->value ?? 'unit' }}</span>
                    </div>
                    @php
                        $latest_change = null;
                        if ($weekly_trend->count() >= 2) {
                            $latest = $weekly_trend->last();
                            $previous = $weekly_trend->get($weekly_trend->count() - 2);
                            if ($previous->average_price > 0) {
                                $latest_change = (($latest->average_price - $previous->average_price) / $previous->average_price) * 100;
                            }
                        }
                    @endphp
                    <div class="mt-2 flex justify-end">
                        @if ($latest_change !== null)
                            <span class="px-3 py-1.5 rounded-lg font-mono font-bold text-lg {{ $latest_change >= 0 ? 'bg-up/10 text-up' : 'bg-down/10 text-down' }}">
                                {{ $latest_change >= 0 ? '▲' : '▼' }} {{ number_format(abs($latest_change), 1) }}% <span class="text-xs font-sans uppercase ml-1">vs last week</span>
                            </span>
                        @else
                            <span class="bg-stone/10 px-3 py-1.5 rounded-lg text-bark font-mono font-bold text-lg">—</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Main Chart Area --}}
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="font-display text-2xl font-bold text-soil">Price Trend</h3>
                    <div class="bg-white border border-stone rounded-[12px] p-6 shadow-sm min-h-[400px] flex flex-col relative overflow-hidden group">
                        <canvas id="priceChart"></canvas>
                    </div>

                    {{-- Link to listings --}}
                    <div class="bg-primary/5 border border-primary/20 rounded-[12px] p-8 text-center relative overflow-hidden">
                        <div class="absolute -right-8 -bottom-8 text-8xl opacity-10 pointer-events-none">{{ $icon }}</div>
                        <h4 class="font-display font-bold text-2xl text-soil mb-2">Looking to buy or sell {{ $commodity->name }}?</h4>
                        <p class="text-bark mb-6 text-lg">Connect directly with farmers and buyers across Kenya.</p>
                        <a href="{{ route('listings.index', ['commodity_id' => $commodity->id]) }}" class="btn-primary inline-flex items-center gap-2 px-8 py-3 text-lg">
                            View {{ $commodity->name }} Listings →
                        </a>
                    </div>
                </div>

                {{-- Side Info --}}
                <div class="space-y-6">
                    {{-- Price Range Card --}}
                    <div class="bg-white border border-stone rounded-[12px] p-6 shadow-sm">
                        <h3 class="font-display text-xl font-bold text-soil mb-6 flex items-center gap-2">
                            <span>📍</span> Price Range
                        </h3>
                        <div class="space-y-6">
                            <div class="bg-down/5 p-4 rounded-xl border border-down/10">
                                <p class="text-xs text-bark uppercase font-bold mb-1 tracking-widest">Lowest Recorded</p>
                                <p class="text-3xl font-bold text-down font-mono">
                                    <span class="text-sm font-sans mr-1">KES</span>{{ $price_range ? number_format($price_range->min_price, 0) : '0' }}
                                </p>
                            </div>
                            <div class="bg-up/5 p-4 rounded-xl border border-up/10">
                                <p class="text-xs text-bark uppercase font-bold mb-1 tracking-widest">Highest Recorded</p>
                                <p class="text-3xl font-bold text-up font-mono">
                                    <span class="text-sm font-sans mr-1">KES</span>{{ $price_range ? number_format($price_range->max_price, 0) : '0' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Average Price by County --}}
                    <div class="bg-white border border-stone rounded-[12px] p-6 shadow-sm">
                        <h3 class="font-display text-xl font-bold text-soil mb-6 flex items-center gap-2">
                            <span>🗺️</span> County Averages
                        </h3>
                        @if ($county_prices->isEmpty())
                            <p class="text-bark text-sm py-4">No active listings for {{ $commodity->name }} yet.</p>
                        @else
                            <div class="space-y-5">
                                @php $max = $county_prices->max('average_price'); @endphp
                                @foreach ($county_prices as $row)
                                    <div>
                                        <div class="flex justify-between text-sm mb-2">
                                            <span class="font-bold text-soil">{{ $row->county }}</span>
                                            <span class="font-mono text-primary font-bold">KES {{ number_format($row->average_price, 0) }}</span>
                                        </div>
                                        <div class="bg-stone/20 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: 0%" data-width="{{ ($row->average_price / $max) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Records Table --}}
            <div class="space-y-6 mt-12">
                <h3 class="font-display text-2xl font-bold text-soil">Weekly Breakdown</h3>
                <div class="bg-white border border-stone rounded-[12px] shadow-sm overflow-hidden">
                    @if ($weekly_trend->isEmpty())
                        <div class="p-12 text-center text-bark font-bold">Not enough data yet to show a trend.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-parchment/50 border-b border-stone">
                                    <tr>
                                        <th class="px-8 py-5 font-bold text-soil uppercase text-xs tracking-widest">Week Of</th>
                                        <th class="px-8 py-5 font-bold text-soil uppercase text-xs tracking-widest text-right">Avg Price</th>
                                        <th class="px-8 py-5 font-bold text-soil uppercase text-xs tracking-widest text-center">Listings</th>
                                        <th class="px-8 py-5 font-bold text-soil uppercase text-xs tracking-widest text-right">Change</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone">
                                    @foreach ($weekly_trend as $i => $week)
                                        @php
                                            $prev   = $i > 0 ? $weekly_trend[$i - 1]->average_price : null;
                                            $change = ($prev && $prev > 0) ? (($week->average_price - $prev) / $prev) * 100 : null;
                                        @endphp
                                        <tr class="hover:bg-parchment/30 transition-colors">
                                            <td class="px-8 py-4 text-soil font-bold">
                                                {{ \Carbon\Carbon::parse($week->week)->format('M d, Y') }}
                                            </td>
                                            <td class="px-8 py-4 text-right font-mono text-soil font-bold text-lg">
                                                <span class="text-xs font-sans text-bark font-normal mr-1">KES</span>{{ number_format($week->average_price, 0) }}
                                            </td>
                                            <td class="px-8 py-4 text-center text-bark font-bold">
                                                {{ $week->listing_count }}
                                            </td>
                                            <td class="px-8 py-4 text-right font-mono font-bold">
                                                @if ($change === null)
                                                    <span class="text-stone">—</span>
                                                @elseif ($change > 0)
                                                    <span class="text-up bg-up/10 px-2 py-1 rounded">▲ {{ number_format(abs($change), 1) }}%</span>
                                                @elseif ($change < 0)
                                                    <span class="text-down bg-down/10 px-2 py-1 rounded">▼ {{ number_format(abs($change), 1) }}%</span>
                                                @else
                                                    <span class="text-bark bg-stone/20 px-2 py-1 rounded">0%</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress bars
            setTimeout(() => {
                document.querySelectorAll('[data-width]').forEach(el => {
                    el.style.width = el.dataset.width;
                });
            }, 300);

            // Initialize Chart
            const ctx = document.getElementById('priceChart').getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(85, 107, 47, 0.2)');
            gradient.addColorStop(1, 'rgba(85, 107, 47, 0)');

            const data = {
                labels: {!! json_encode($weekly_trend->pluck('week')->map(fn($w) => \Carbon\Carbon::parse($w)->format('M d'))->toArray()) !!},
                datasets: [{
                    label: 'Average Price (KES)',
                    data: {!! json_encode($weekly_trend->pluck('average_price')->toArray()) !!},
                    borderColor: '#556B2F',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#556B2F',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            };

            new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1B120B',
                            titleFont: { family: 'DM Sans', size: 14, weight: 'bold' },
                            bodyFont: { family: 'DM Mono', size: 13 },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'KES ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                font: { family: 'DM Mono', size: 11 },
                                callback: value => 'KES ' + value.toLocaleString()
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'DM Sans', size: 11 } }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
