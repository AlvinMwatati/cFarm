<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Enums\CommodityCategory;
use App\Enums\CommodityUnit;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property CommodityCategory $category
 * @property CommodityUnit $unit
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommodityFollow> $followers
 * @property-read int|null $followers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity byCategory(\App\Enums\CommodityCategory $category)
 * @method static \Database\Factories\CommodityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commodity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Commodity extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'category',
        'unit',
        'description',
        'is_active',
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'category'  => CommodityCategory::class,
        'unit'      => CommodityUnit::class,
        'is_active' => 'boolean',
    ];

    // Auto-generate slug from name
    protected static function booted(): void
    {
        static::creating(function (Commodity $commodity) {
            $commodity->slug = Str::slug($commodity->name);
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, CommodityCategory $category)
    {
        return $query->where('category', $category);
    }

    // Relationships
    public function followers(): HasMany
    {
        return $this->hasMany(CommodityFollow::class);
    }


}
