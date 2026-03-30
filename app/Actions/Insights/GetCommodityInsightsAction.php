<?php

namespace App\Actions\Insights;

use App\Contracts\PricingSourceInterface;
use App\Models\Commodity;

class GetCommodityInsightsAction
{
    public function __construct(
        private readonly PricingSourceInterface $pricingSource
    ) {}

    public function execute(Commodity $commodity): array
    {
        return [
            'commodity'    => $commodity,
            'county_prices' => $this->pricingSource->getAveragePricesByCounty($commodity),
            'price_range'   => $this->pricingSource->getPriceRange($commodity),
            'weekly_trend'  => $this->pricingSource->getWeeklyTrend($commodity),
        ];
    }
}
