<x-app-layout>
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-primary-dark via-primary to-soil text-parchment">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-8">
                    <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-black leading-tight tracking-tight">
                        Kenya's Farm Prices, <br>
                        <span class="text-accent-light italic">In Your Hands.</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-mist max-w-2xl leading-relaxed">
                        Real government market data from KAMIS. Updated every morning.<br>
                        Know what maize sells for in Meru before you drive to market.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="{{ route('insights.index') }}" class="inline-flex justify-center items-center px-8 py-4 bg-accent text-soil font-bold rounded-lg hover:bg-accent-light transition-colors duration-200">
                            See Today's Prices
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-8 py-4 bg-transparent border-2 border-mist text-mist font-bold rounded-lg hover:bg-white/10 transition-colors duration-200">
                            Sell Your Produce →
                        </a>
                    </div>
                </div>

                <!-- Animated Ticker Area -->
                <div class="lg:col-span-5 hidden lg:block">
                    <div class="bg-soil/40 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden h-[400px]">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-soil/80 pointer-events-none z-10"></div>
                        <div class="space-y-4 animate-[slideUp_20s_linear_infinite]">
                            @php
                                $dummyTicker = [
                                    ['icon' => '🌽', 'name' => 'Maize (Dry)', 'price' => '45.50', 'unit' => 'kg', 'trend' => 'up', 'change' => '2%'],
                                    ['icon' => '🍅', 'name' => 'Tomatoes', 'price' => '82.00', 'unit' => 'kg', 'trend' => 'down', 'change' => '5%'],
                                    ['icon' => '🥔', 'name' => 'Irish Potatoes', 'price' => '3500', 'unit' => 'bag', 'trend' => 'neutral', 'change' => '0%'],
                                    ['icon' => '🌿', 'name' => 'Kale (Sukuma)', 'price' => '12.00', 'unit' => 'bundle', 'trend' => 'up', 'change' => '1%'],
                                    ['icon' => '🥛', 'name' => 'Cow Milk', 'price' => '60.00', 'unit' => 'liter', 'trend' => 'neutral', 'change' => '0%'],
                                    ['icon' => '🧅', 'name' => 'Onions', 'price' => '120.00', 'unit' => 'kg', 'trend' => 'up', 'change' => '8%'],
                                ];
                            @endphp
                            <!-- Double the array for smooth infinite scroll -->
                            @foreach(array_merge($dummyTicker, $dummyTicker) as $item)
                            <div class="bg-white/5 border border-white/10 rounded-xl p-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-3xl">{{ $item['icon'] }}</span>
                                    <div>
                                        <h3 class="font-bold text-white">{{ $item['name'] }}</h3>
                                        <div class="text-sm text-mist/80">KES {{ $item['price'] }}/{{ $item['unit'] }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($item['trend'] === 'up')
                                        <span class="text-[#4ADE80] font-mono font-bold flex items-center gap-1">▲ {{ $item['change'] }}</span>
                                    @elseif($item['trend'] === 'down')
                                        <span class="text-[#F87171] font-mono font-bold flex items-center gap-1">▼ {{ $item['change'] }}</span>
                                    @else
                                        <span class="text-stone font-mono font-bold flex items-center gap-1">— {{ $item['change'] }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Proof Bar -->
    <div class="bg-soil text-stone py-4 border-b border-white/5 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center text-sm font-mono tracking-wider overflow-x-auto whitespace-nowrap gap-8 no-scrollbar">
                <span>32,415 Price Records</span>
                <span class="hidden sm:inline">•</span>
                <span>170 Commodities</span>
                <span class="hidden sm:inline">•</span>
                <span>47 Counties</span>
                <span class="hidden sm:inline">•</span>
                <span class="text-accent-light">Updated Daily 6AM</span>
            </div>
        </div>
    </div>

    <!-- Why Farmers Trust cFarm -->
    <div class="py-20 bg-parchment">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4">
                    <div class="text-5xl">📊</div>
                    <h3 class="text-xl font-bold text-soil font-display">Real Government Prices</h3>
                    <p class="text-bark leading-relaxed">
                        We pull directly from KAMIS — the official Kenya Agricultural Market Information System — every morning at 6AM.
                    </p>
                </div>
                <div class="space-y-4">
                    <div class="text-5xl">🔔</div>
                    <h3 class="text-xl font-bold text-soil font-display">Price Alerts</h3>
                    <p class="text-bark leading-relaxed">
                        Follow any commodity and get notified when prices spike or drop beyond your threshold.
                    </p>
                </div>
                <div class="space-y-4">
                    <div class="text-5xl">🤝</div>
                    <h3 class="text-xl font-bold text-soil font-display">No Middlemen</h3>
                    <p class="text-bark leading-relaxed">
                        Post your produce and connect directly with buyers. No commission. No gatekeepers.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Market Insights -->
    <div class="py-20 bg-sand">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-display font-bold text-soil mb-2">Market Insights</h2>
                    <p class="text-bark">Today's top movers across the country</p>
                </div>
                <a href="{{ route('insights.index') }}" class="text-primary font-bold hover:underline hidden sm:block">View all prices →</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <a href="{{ route('insights.show', 1) }}" class="block group">
                    <div class="stat-card hover:border-primary transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-4xl">🌽</span>
                            <span class="stat-change up bg-up/10 px-2 py-1 rounded text-up">▲ 12% week</span>
                        </div>
                        <h3 class="font-display font-bold text-xl text-soil mb-1">Maize</h3>
                        <div class="font-mono text-2xl font-medium text-soil">KES 45.50<span class="text-sm text-bark font-sans"> /kg</span></div>
                        <div class="mt-4 text-primary text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">View Prices →</div>
                    </div>
                </a>
                <!-- Card 2 -->
                <a href="{{ route('insights.show', 2) }}" class="block group">
                    <div class="stat-card hover:border-primary transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-4xl">🍅</span>
                            <span class="stat-change down bg-down/10 px-2 py-1 rounded text-down">▼ 5% week</span>
                        </div>
                        <h3 class="font-display font-bold text-xl text-soil mb-1">Tomatoes</h3>
                        <div class="font-mono text-2xl font-medium text-soil">KES 82.00<span class="text-sm text-bark font-sans"> /kg</span></div>
                        <div class="mt-4 text-primary text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">View Prices →</div>
                    </div>
                </a>
                <!-- Card 3 -->
                <a href="{{ route('insights.show', 3) }}" class="block group">
                    <div class="stat-card hover:border-primary transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-4xl">🥔</span>
                            <span class="stat-change neutral bg-stone/20 px-2 py-1 rounded text-neutral">— steady</span>
                        </div>
                        <h3 class="font-display font-bold text-xl text-soil mb-1">Potatoes</h3>
                        <div class="font-mono text-2xl font-medium text-soil">KES 3500<span class="text-sm text-bark font-sans"> /bag</span></div>
                        <div class="mt-4 text-primary text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">View Prices →</div>
                    </div>
                </a>
            </div>
            <div class="mt-6 sm:hidden text-center">
                <a href="{{ route('insights.index') }}" class="text-primary font-bold hover:underline">View all prices →</a>
            </div>
        </div>
    </div>

    <!-- Latest Listings -->
    <div class="py-20 bg-parchment border-t border-stone/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-display font-bold text-soil mb-2">Fresh on the Market</h2>
                    <p class="text-bark">Direct from farmers across Kenya</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Dummy listings -->
                @for($i=1; $i<=6; $i++)
                <div class="bg-white rounded-[12px] border border-stone overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="h-48 bg-primary-muted flex items-center justify-center text-6xl">
                        {{ ['🌽', '🍅', '🥔', '🧅', '🥬', '🥕'][$i-1] }}
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg text-soil truncate">Fresh {{ ['Maize', 'Tomatoes', 'Potatoes', 'Onions', 'Cabbages', 'Carrots'][$i-1] }}</h3>
                            <span class="bg-secondary-muted text-secondary-dark text-xs font-bold px-2 py-1 rounded-full whitespace-nowrap">{{ $i * 10 }} units</span>
                        </div>
                        <div class="font-mono text-xl text-soil mb-4">KES {{ number_format(rand(40, 300), 2) }}<span class="text-xs text-bark font-sans">/unit</span></div>
                        <div class="space-y-2 text-sm text-bark mb-5">
                            <div class="flex items-center gap-2">📍 <span>{{ ['Nairobi', 'Nakuru', 'Meru', 'Kiambu', 'Mombasa', 'Nyeri'][$i-1] }}</span></div>
                            <div class="flex items-center gap-2">👤 <span>Farmer {{ $i }}</span></div>
                        </div>
                        <a href="{{ route('listings.show', $i) }}" class="block w-full text-center py-2 border-2 border-stone text-soil font-bold rounded-md hover:border-primary hover:text-primary transition-colors">View Listing</a>
                    </div>
                </div>
                @endfor
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('listings.index') }}" class="btn-primary inline-flex items-center gap-2">View All Listings →</a>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="py-24 bg-sand border-t border-stone/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-display font-bold text-soil text-center mb-16">How cFarm Works</h2>

            <div class="relative max-w-4xl mx-auto">
                <!-- Connecting Line -->
                <div class="hidden md:block absolute top-6 left-16 right-16 border-t-2 border-dashed border-stone"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center relative z-10">
                    <div class="space-y-4">
                        <div class="w-12 h-12 mx-auto bg-primary text-white rounded-full flex items-center justify-center font-bold text-xl ring-8 ring-sand">1</div>
                        <h3 class="font-bold text-soil text-lg">Register & Post</h3>
                        <p class="text-bark">Create a free account and post your produce with details and photos.</p>
                    </div>
                    <div class="space-y-4">
                        <div class="w-12 h-12 mx-auto bg-primary text-white rounded-full flex items-center justify-center font-bold text-xl ring-8 ring-sand">2</div>
                        <h3 class="font-bold text-soil text-lg">Buyers Find You</h3>
                        <p class="text-bark">Buyers search by county and commodity to find exactly what they need.</p>
                    </div>
                    <div class="space-y-4">
                        <div class="w-12 h-12 mx-auto bg-primary text-white rounded-full flex items-center justify-center font-bold text-xl ring-8 ring-sand">3</div>
                        <h3 class="font-bold text-soil text-lg">Connect Directly</h3>
                        <p class="text-bark">No commission. No middleman. Contact each other directly to close the deal.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Banner -->
    <div class="bg-primary text-white py-16">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-8">
            <h2 class="text-3xl md:text-5xl font-display font-bold">Stop guessing. <br><span class="text-primary-muted italic">Start selling at the right price.</span></h2>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-primary font-bold rounded-md hover:bg-parchment transition-colors shadow-lg hover:shadow-xl">Create Free Account</a>
                <a href="{{ route('insights.index') }}" class="px-8 py-4 bg-primary-dark border border-primary-light text-white font-bold rounded-md hover:bg-soil transition-colors">View Market Prices</a>
            </div>
        </div>
    </div>

    {{-- <!-- Footer -->
    <footer class="bg-soil text-stone py-12 border-t border-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8 border-b border-stone/20 pb-8">
                <div class="col-span-1 md:col-span-2">
                    <a href="/" class="cfarm-logo logo-inverted mb-4 inline-block">
                        <span class="logo-c">c</span><span class="logo-farm">Farm</span>
                    </a>
                    <p class="text-stone/80 text-sm max-w-sm mt-2">
                        Kenya's farm produce marketplace connecting farmers directly with buyers using real government price data.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Platform</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('commodities.index') }}" class="hover:text-white transition-colors">Browse Produce</a></li>
                        <li><a href="{{ route('insights.index') }}" class="hover:text-white transition-colors">Market Insights</a></li>
                        <li><a href="{{ route('listings.create') }}" class="hover:text-white transition-colors">Sell Produce</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center text-xs text-stone/60">
                <p>&copy; {{ date('Y') }} cFarm. All rights reserved.</p>
                <p class="mt-2 md:mt-0">Price data sourced from KAMIS (Kenya Agricultural Market Information System).</p>
            </div>
        </div>
    </footer> --}}

    <!-- Add required animation styles inline for the ticker -->
    <style>
        @keyframes slideUp {
            0% { transform: translateY(0); }
            100% { transform: translateY(-50%); }
        }
        /* Hide scrollbar for the horizontal scrolling elements */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</x-app-layout>
