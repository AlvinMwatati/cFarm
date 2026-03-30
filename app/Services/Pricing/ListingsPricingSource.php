<?php

namespace App\Services\Pricing;

use App\Contracts\PricingSourceInterface;
use App\Models\Commodity;
use App\Models\Listing;
use App\Enums\ListingStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ListingsPricingSource implements PricingSourceInterface
{
    public function getAveragePricesByCounty(Commodity $commodity): Collection
    {
        return Listing::query()
            ->where('commodity_id', $commodity->id)
            ->where('status', ListingStatus::ACTIVE)
            ->select('county', DB::raw('AVG(price_per_unit) as average_price'))
            ->groupBy('county')
            ->orderByDesc('average_price')
            ->get()
            ->map(fn($row) => (object) [
                'county'        => $row->county,
                'average_price' => round($row->average_price, 2),
                'unit'          => $commodity->unit->value,
            ]);
    }

    public function getPriceRange(Commodity $commodity): object
    {
        $result = Listing::query()
            ->where('commodity_id', $commodity->id)
            ->where('status', ListingStatus::ACTIVE)
            ->select(
                DB::raw('MIN(price_per_unit) as min_price'),
                DB::raw('MAX(price_per_unit) as max_price'),
            )
            ->first();

        return (object) [
            'min_price' => $result?->min_price ?? 0,
            'max_price' => $result?->max_price ?? 0,
            'unit'      => $commodity->unit->value,
        ];
    }

    public function getWeeklyTrend(Commodity $commodity): Collection
    {
        $connection = config('database.default');

        // Use the correct date grouping function per database
        $weekExpression = $connection === 'sqlite'
            ? "strftime('%Y-%W', created_at)"
            : "YEARWEEK(created_at, 1)";

        $weekStart = $connection === 'sqlite'
            ? "MIN(DATE(created_at))"
            : "MIN(DATE(created_at))";

        return Listing::query()
            ->where('commodity_id', $commodity->id)
            ->where('status', ListingStatus::ACTIVE)
            ->where('created_at', '>=', now()->subWeeks(8))
            ->select(
                DB::raw("{$weekExpression} as week_key"),
                DB::raw("{$weekStart} as week_start"),
                DB::raw('AVG(price_per_unit) as average_price'),
                DB::raw('COUNT(*) as listing_count'),
            )
            ->groupBy('week_key')
            ->orderBy('week_key')
            ->get()
            ->map(fn($row) => (object) [
                'week'          => $row->week_start,
                'average_price' => round($row->average_price, 2),
                'listing_count' => $row->listing_count,
            ]);
    }

    public function getMostListedCommodities(): Collection
    {
        return Listing::query()
            ->where('status', ListingStatus::ACTIVE)
            ->select('commodity_id', DB::raw('COUNT(*) as listing_count'))
            ->groupBy('commodity_id')
            ->orderByDesc('listing_count')
            ->limit(10)
            ->with('commodity')
            ->get()
            ->map(fn($row) => (object) [
                'commodity'     => $row->commodity,
                'listing_count' => $row->listing_count,
            ]);
    }
}
