<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $commodity_name
 * @property string|null $classification
 * @property string $market
 * @property numeric|null $wholesale_price
 * @property numeric|null $retail_price
 * @property string $unit
 * @property numeric|null $supply_volume
 * @property string $county
 * @property \Illuminate\Support\Carbon $price_date
 * @property string $source
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice forCommodity(string $name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice forCounty(string $county)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice forMarket(string $market)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice recent(int $days = 30)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereCommodityName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereMarket($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice wherePriceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereRetailPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereSupplyVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketPrice whereWholesalePrice($value)
 * @mixin \Eloquent
 */
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
