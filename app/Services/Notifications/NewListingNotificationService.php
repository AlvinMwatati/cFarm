<?php

namespace App\Services\Notifications;

use App\Models\CommodityFollow;
use App\Models\Listing;
use App\Notifications\NewListingAlert;

class NewListingNotificationService
{
    public function notify(Listing $listing): void
    {
        $follows = CommodityFollow::where('commodity_id', $listing->commodity_id)
            ->where('notify_new_listing', true)
            ->where('user_id', '!=', $listing->user_id) // don't notify the poster
            ->with('user')
            ->get();

        foreach ($follows as $follow) {
            $follow->user->notify(new NewListingAlert($listing));
        }
    }
}
