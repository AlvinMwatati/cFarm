<?php

namespace App\Http\Controllers\Insights;

use App\Actions\Insights\GetCommodityInsightsAction;
use App\Actions\Insights\GetPlatformInsightsAction;
use App\Http\Controllers\Controller;
use App\Models\Commodity;
use App\Models\Listing;
use App\Models\MarketPrice;
use App\Enums\CommodityCategory;

class InsightsController extends Controller
{
    // Dedicated /insights page — platform overview
    public function index(GetPlatformInsightsAction $action): \Illuminate\View\View
    {
        $commodities = Commodity::active()
            // ->with(['latestPrice', 'previousPrice'])
            ->orderBy('name', 'asc')
            ->get();

        $insights = $action->execute();

        $categories = CommodityCategory::cases();
        $activeListingsCount = Listing::active()->count('id');
        $priceRecordsCount = MarketPrice::count('id');

        return view('insights.index', array_merge(
            compact('commodities', 'categories', 'activeListingsCount', 'priceRecordsCount'),
            $insights
        ));
    }

    // Per-commodity insights — shown on /insights/{commodity}
    public function show(
        Commodity $commodity,
        GetCommodityInsightsAction $action
    ): \Illuminate\View\View {
        $insights = $action->execute($commodity);

        return view('insights.show', $insights);
    }
}
