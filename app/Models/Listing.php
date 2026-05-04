<?php

namespace App\Models;

use App\Enums\KenyaCounty;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ListingStatus;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $commodity_id
 * @property string $title
 * @property string|null $description
 * @property numeric $price_per_unit
 * @property int $quantity_available
 * @property int $minimum_order_quantity
 * @property KenyaCounty $county
 * @property string|null $town
 * @property ListingStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Commodity $commodity
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing byCommodity(int $commodityId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing byCounty(string $county)
 * @method static \Database\Factories\ListingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereCommodityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereMinimumOrderQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing wherePricePerUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereQuantityAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereUserId($value)
 * @mixin \Eloquent
 */
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
        'county'        => KenyaCounty::class,
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
