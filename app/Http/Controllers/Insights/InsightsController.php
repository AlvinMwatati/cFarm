<?php

namespace App\Http\Controllers\Insights;

use App\Actions\Insights\GetCommodityInsightsAction;
use App\Actions\Insights\GetPlatformInsightsAction;
use App\Http\Controllers\Controller;
use App\Models\Commodity;

class InsightsController extends Controller
{
    // Dedicated /insights page — platform overview
    public function index(GetPlatformInsightsAction $action): \Illuminate\View\View
    {
        $commodities = Commodity::active()->orderBy('name')->get();
        $insights    = $action->execute();

        return view('insights.index', array_merge(
            compact('commodities'),
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
