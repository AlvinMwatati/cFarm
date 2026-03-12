<?php

namespace App\Enums;

enum CommodityUnit: string
{
    case KG     = 'kg';
    case BAG    = 'bag';
    case TON    = 'ton';
    case CRATE  = 'crate';
    case LITRE  = 'litre';
    case DOZEN  = 'dozen';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
