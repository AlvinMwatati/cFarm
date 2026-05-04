<?php

namespace App\Services\Notifications;

use App\Enums\KenyaCounty;
use App\Models\Commodity;
use App\Models\CommodityFollow;
use App\Notifications\PriceDropAlert;
use App\Notifications\PriceSpikeAlert;

class PriceAlertService
{
    public function checkAndNotify(
        Commodity $commodity,
        float     $previousPrice,
        float     $currentPrice,
        KenyaCounty $county,
    ): void {
        if ($previousPrice <= 0) return;

        $changePercent = round((($currentPrice - $previousPrice) / $previousPrice) * 100, 2);
        $absChange     = abs($changePercent);

        // Get all followers of this commodity
        $follows = CommodityFollow::where('commodity_id', $commodity->id)
            ->with('user')
            ->get();

        foreach ($follows as $follow) {
            // Check if change exceeds this user's threshold
            if ($absChange < $follow->price_change_threshold) {
                continue;
            }

            if ($changePercent < 0 && $follow->notify_price_drop) {
                $follow->user->notify(new PriceDropAlert(
                    commodity:     $commodity,
                    previousPrice: $previousPrice,
                    currentPrice:  $currentPrice,
                    changePercent: $absChange,
                    county:        $county,
                ));
            }

            if ($changePercent > 0 && $follow->notify_price_spike) {
                $follow->user->notify(new PriceSpikeAlert(
                    commodity:     $commodity,
                    previousPrice: $previousPrice,
                    currentPrice:  $currentPrice,
                    changePercent: $absChange,
                    county:        $county,
                ));
            }
        }
    }
}
