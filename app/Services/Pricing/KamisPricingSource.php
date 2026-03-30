<?php

namespace App\Services\Pricing;

use App\Contracts\PricingSourceInterface;
use App\Models\Commodity;
use App\Models\MarketPrice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Listing;
use App\Enums\ListingStatus;


class KamisPricingSource implements PricingSourceInterface
{
    protected $listingsPricingSource;

    public function __construct(ListingsPricingSource $listingsPricingSource){
        $this->listingsPricingSource = $listingsPricingSource;
    }

    public function getAveragePricesByCounty(Commodity $commodity): Collection
    {
        $results = MarketPrice::recent(30)
            ->forCommodity(KamisCommodityMapper::toKamis($commodity->name))
            ->select('county', DB::raw('AVG(retail_price) as average_price'))
            ->whereNotNull('retail_price')
            ->groupBy('county')
            ->orderByDesc('average_price')
            ->get();

        if ($results->isEmpty()) {
            // Fallback to listings if no KAMIS data
            return $this->listingsPricingSource->getAveragePricesByCounty($commodity);
        }

        return $results->map(fn ($row) => (object) [
            'county'        => $row->county,
            'average_price' => round($row->average_price, 2),
            'unit'          => $commodity->unit->value,
        ]);
    }

    public function getPriceRange(Commodity $commodity): object
    {
        $result = MarketPrice::recent(30)
            ->forCommodity($commodity->name)
            ->whereNotNull('retail_price')
            ->select(
                DB::raw('MIN(retail_price) as min_price'),
                DB::raw('MAX(retail_price) as max_price'),
            )
            ->first();

        if (!$result || !$result->min_price) {
            return $this->listingsPricingSource->getPriceRange($commodity);
        }

        return (object) [
            'min_price' => $result->min_price,
            'max_price' => $result->max_price,
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
        ->map(fn ($row) => (object) [
            'week'          => $row->week_start,
            'average_price' => round($row->average_price, 2),
            'listing_count' => $row->listing_count,
        ]);
}

    public function getMostListedCommodities(): Collection
    {
        // KAMIS tracks supply volume, not listing count
        // Use listings data for this metric
        return $this->listingsPricingSource->getMostListedCommodities();
    }
}
