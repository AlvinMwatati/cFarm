<?php

namespace App\Enums;

enum CommodityCategory: string
{
    case GRAINS     = 'grains';
    case VEGETABLES = 'vegetables';
    case FRUITS     = 'fruits';
    case LEGUMES    = 'legumes';
    case TUBERS     = 'tubers';
    case DAIRY      = 'dairy';
    case POULTRY    = 'poultry';
    case OTHER      = 'other';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
