<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScraperLog;
use App\Models\MarketPrice;
use Illuminate\Support\Facades\Artisan;

class ScraperController extends Controller
{
    public function index()
    {
        $logs             = ScraperLog::latest()->paginate(15);
        $lastRun          = ScraperLog::latest()->first();
        $totalPrices      = MarketPrice::count();
        $uniqueCommodities = MarketPrice::distinct('commodity_name')->count('commodity_name');
        $latestPriceDate  = MarketPrice::max('price_date');

        return view('admin.scraper', compact(
            'logs', 'lastRun', 'totalPrices', 'uniqueCommodities', 'latestPriceDate'
        ));
    }

    public function run()
    {
        // Run asynchronously via artisan command
        Artisan::queue('kamis:scrape');

        return back()->with('success', 'KAMIS scrape job queued. Check logs for progress.');
    }
}
