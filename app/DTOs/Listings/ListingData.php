<?php

namespace App\DTOs\Listings;

use App\Enums\KenyaCounty;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

class ListingData extends Data
{
    public function __construct(
        public readonly int     $commodity_id,
            #[Min(3), Max(100)]
        public readonly string  $title,
            #[Min(3), Max(255)]
        public readonly ?string $description,
        public readonly float   $price_per_unit,
        public readonly int     $quantity_available,
        public readonly int     $minimum_order_quantity,
        public readonly KenyaCounty $county,
        public readonly ?string $town,
    ) {}
}
