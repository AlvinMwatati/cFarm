<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
