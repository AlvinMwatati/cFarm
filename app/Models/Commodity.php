<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Enums\CommodityCategory;
use App\Enums\CommodityUnit;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
