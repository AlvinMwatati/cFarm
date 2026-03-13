<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ListingStatus;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'commodity_id',
        'title',
        'description',
        'price_per_unit',
        'quantity_available',
        'minimum_order_quantity',
        'county',
        'town',
        'status',
    ];

    protected $casts = [
        'status'        => ListingStatus::class,
        'price_per_unit' => 'decimal:2',
    ];


    //Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }

    //Media
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(400)
             ->height(300);
    }

    //Scopes
     public function scopeActive($query)
    {
        return $query->where('status', ListingStatus::ACTIVE);
    }

    public function scopeByCounty($query, string $county)
    {
        return $query->where('county', $county);
    }

    public function scopeByCommodity($query, int $commodityId)
    {
        return $query->where('commodity_id', $commodityId);
    }
}
