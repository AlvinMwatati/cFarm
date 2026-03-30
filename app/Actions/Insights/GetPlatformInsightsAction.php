<?php

namespace App\Actions\Insights;

use App\Contracts\PricingSourceInterface;

class GetPlatformInsightsAction
{
    public function __construct(
        private readonly PricingSourceInterface $pricingSource
    ) {}

    public function execute(): array
    {
        return [
            'most_listed' => $this->pricingSource->getMostListedCommodities(),
        ];
    }
}
