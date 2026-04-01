<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'county',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // User can have many listings
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    // User can follow many commodities
    public function commodityFollows(): HasMany
    {
        return $this->hasMany(CommodityFollow::class);
    }

    // User can follow many commodities through the pivot table
    public function followedCommodities(): BelongsToMany
    {
        return $this->belongsToMany(Commodity::class, 'commodity_follows')
                    ->withPivot([
                        'notify_price_drop',
                        'notify_price_spike',
                        'notify_new_listing',
                        'notify_weekly_summary',
                        'via_app',
                        'via_email',
                        'via_sms',
                        'price_change_threshold',
                    ])
                    ->withTimestamps();
    }

    // Check if user is following a specific commodity
    public function isFollowingCommodity(Commodity $commodity): bool
    {
        return $this->followedCommodities()->where('commodity_id', $commodity->id)->exists();
    }
}
