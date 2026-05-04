<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $commodity_id
 * @property bool $notify_price_drop
 * @property bool $notify_price_spike
 * @property bool $notify_new_listing
 * @property bool $notify_weekly_summary
 * @property bool $via_app
 * @property bool $via_email
 * @property bool $via_sms
 * @property numeric $price_change_threshold
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Commodity $commodity
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CommodityFollowFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereCommodityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereNotifyNewListing($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereNotifyPriceDrop($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereNotifyPriceSpike($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereNotifyWeeklySummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow wherePriceChangeThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereViaApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereViaEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommodityFollow whereViaSms($value)
 * @mixin \Eloquent
 */
class CommodityFollow extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

        /**
        * The attributes that are mass assignable.
        *
        * @var list<string>
        */
    protected $fillable = [
        'user_id',
        'commodity_id',
        'notify_price_drop',
        'notify_price_spike',
        'notify_new_listing',
        'notify_weekly_summary',
        'via_app',
        'via_email',
        'via_sms',
        'price_change_threshold',
    ];

    protected $casts = [
        'notify_price_drop' => 'boolean',
        'notify_price_spike' => 'boolean',
        'notify_new_listing' => 'boolean',
        'notify_weekly_summary' => 'boolean',
        'via_app' => 'boolean',
        'via_email' => 'boolean',
        'via_sms' => 'boolean',
        'price_change_threshold' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commodity(): BelongsTo

    {
        return $this->belongsTo(Commodity::class);
    }


}
