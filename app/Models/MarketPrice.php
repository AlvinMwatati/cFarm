<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    protected $fillable = [
        'commodity_name',
        'classification',
        'market',
        'wholesale_price',
        'retail_price',
        'unit',
        'supply_volume',
        'county',
        'price_date',
        'source',
    ];

    protected $casts = [
        'price_date'      => 'date',
        'wholesale_price' => 'decimal:2',
        'retail_price'    => 'decimal:2',
        'supply_volume'   => 'decimal:2',
    ];

    // Scope to get recent prices only
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('price_date', '>=', now()->subDays($days));
    }

    public function scopeForCommodity($query, string $name)
    {
        return $query->where('commodity_name', 'like', "%{$name}%");
    }
    public function scopeForCounty($query, string $county)
    {
        return $query->where('county', $county);
    }
    public function scopeForMarket($query, string $market)
    {
        return $query->where('market', $market);
    }
}
