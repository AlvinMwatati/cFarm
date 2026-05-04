<?php

namespace App\Services\Pricing;

class KamisCommodityMapper
{
    // Maps your commodity names to KAMIS commodity names
    private static array $map = [
        'Maize'       => 'Dry Maize',
        'Kale'        => 'Kales/Sukuma Wiki',
        'Tomatoes'    => 'Tomatoes',
        'Potatoes'    => 'Red Irish potato',
        'Beans'       => 'Beans Red Haricot',
        'Green Grams' => 'Green Grams',
        'Wheat'       => 'Wheat',
        'Rice'        => 'Rice',
        'Carrots'     => 'Carrots',
        'Cabbages'    => 'Cabbages',
        'Avocado'     => 'Avocado',
        'Mango'       => 'Mangoes',
        'Milk'        => 'Cow Milk(At collection point)',
    ];

    public static function toKamis(string $commodityName): string
    {
        return self::$map[$commodityName] ?? $commodityName;
    }
}
