<?php

namespace App\Contracts;

use App\Models\Commodity;
use Illuminate\Support\Collection;

interface PricingSourceInterface
{
    /**
     * Get average price per county for a commodity.
     * Returns Collection of objects with: county, average_price, unit
     */
    public function getAveragePricesByCounty(Commodity $commodity): Collection;

    /**
     * Get price range (min/max) for a commodity.
     * Returns object with: min_price, max_price, unit
     */
    public function getPriceRange(Commodity $commodity): object;

    /**
     * Get weekly price trend for a commodity.
     * Returns Collection of objects with: week, average_price
     */
    public function getWeeklyTrend(Commodity $commodity): Collection;

    /**
     * Get most listed commodities across the platform.
     * Returns Collection of objects with: commodity, listing_count
     */
    public function getMostListedCommodities(): Collection;
}
