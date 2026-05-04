<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ScrapeKamisJob;
use App\Models\MarketPrice;
use App\Models\ScraperLog;
use Illuminate\Support\Facades\Cache;

class ScraperController extends Controller
{
    public function index()
    {
        $logs              = ScraperLog::latest()->paginate(15);
        $lastRun           = ScraperLog::latest()->first();
        $totalPrices       = MarketPrice::count();
        $uniqueCommodities = MarketPrice::distinct('commodity_name')->count('commodity_name');
        $latestPriceDate   = MarketPrice::max('price_date');
        $scraperStatus     = Cache::get('kamis_scraper_status', ['running' => false, 'message' => null]);

        return view('admin.scraper', compact(
            'logs', 'lastRun', 'totalPrices', 'uniqueCommodities',
            'latestPriceDate', 'scraperStatus'
        ));
    }

    public function run()
    {
        $current = Cache::get('kamis_scraper_status', []);

        if (!empty($current['running'])) {
            return back()->with('error', 'A scrape is already running. Please wait.');
        }

        ScrapeKamisJob::dispatch();

        Cache::put('kamis_scraper_status', [
            'running'    => true,
            'started_at' => now()->toISOString(),
            'message'    => 'Job queued — starting shortly...',
        ], now()->addSeconds(700));

        return back()->with('success', 'Scrape job dispatched. This page will update automatically.');
    }

    public function status()
    {
        return response()->json(
            Cache::get('kamis_scraper_status', ['running' => false, 'message' => null])
        );
    }
}
