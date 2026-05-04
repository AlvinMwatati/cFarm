<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Commodity;
use App\Models\CommodityFollow;
use App\Contracts\PricingSourceInterface;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request, PricingSourceInterface $pricingSource)
    {
        $user = Auth::user();

        $stats = [
            'active_listings'      => $user->listings()->active()->count(),
            'total_listings'       => $user->listings()->count(),
            'followed_commodities' => $user->commodityFollows()->count(),
            'unread_notifications' => $user->unreadNotifications()->count(),
        ];

        // Recent price alerts (notifications)
        $recentAlerts = $user->notifications()->latest()->take(8)->get();

        // Commodities the user is following (with the follow pivot)
        $followedCommodities = $user->commodityFollows()
            ->with('commodity')
            ->latest()
            ->get();

        // Active commodities that have recent price data
        $activeCommodities = Commodity::active()
            ->orderBy('name', 'asc')
            ->take(12)
            ->get();

        return view('dashboard', compact(
            'stats',
            'recentAlerts',
            'followedCommodities',
            'activeCommodities',
        ));
    }
}
