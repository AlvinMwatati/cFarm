<?php

namespace App\DTOs\Commodities;

use App\Enums\CommodityCategory;
use App\Enums\CommodityUnit;
use Spatie\LaravelData\Data;

class CommodityData extends Data
{
    public function __construct(
        public readonly string            $name,
        public readonly CommodityCategory $category,
        public readonly CommodityUnit     $unit,
        public readonly ?string           $description,
    ) {}

}
