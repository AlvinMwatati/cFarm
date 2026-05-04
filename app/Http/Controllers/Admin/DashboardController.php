<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Listing;
use App\Models\Commodity;
use App\Models\MarketPrice;
use App\Models\ScraperLog;
use Illuminate\Notifications\DatabaseNotification;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'      => User::count('id'),
            'active_users'     => User::where('is_banned', false)->count('id'),
            'banned_users'     => User::where('is_banned', true)->count('id'),
            'total_listings'   => Listing::count(),
            'active_listings'  => Listing::where('status', 'active')->count('id'),
            'total_commodities'=> Commodity::count('id'),
            'total_prices'     => MarketPrice::count('id'),
            'notifications_sent' => DatabaseNotification::count(),
        ];

        $lastScrape     = ScraperLog::latest('id')->first();
        $recentListings = Listing::with(['user', 'commodity'])
            ->latest()
            ->take(5)
            ->get();
        $recentUsers    = User::latest('id')->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'lastScrape', 'recentListings', 'recentUsers'
        ));
    }
}
