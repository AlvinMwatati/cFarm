<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commodities\FollowCommodityRequest;
use App\Models\Commodity;
use Illuminate\Http\Request;
use App\Models\CommodityFollow;

class FollowController extends Controller
{
        public function follow(FollowCommodityRequest $request, Commodity $commodity)
        {
            $data = $request->validated();

            CommodityFollow::updateOrCreate(
            [
                'user_id'      => $request->user()->id,
                'commodity_id' => $commodity->id,
            ],

            array_merge([
                'notify_price_drop'      => true,
                'notify_price_spike'     => true,
                'notify_new_listing'     => true,
                'notify_weekly_summary'  => true,
                'via_app'                => true,
                'via_email'              => true,
                'via_sms'                => false,
                'price_change_threshold' => 10,
            ], $data)
            );

        return back()->with('success', "You are now following {$commodity->name}.");

        }

    // Unfollow
    public function unfollow(Commodity $commodity, Request $request)
    {
        CommodityFollow::where('user_id', $request->user()->id)
            ->where('commodity_id', $commodity->id)
            ->delete();

        return back()->with('success', "You have unfollowed {$commodity->name}.");
    }

    // View and manage all follows
    public function index(Request $request)
    {
        $follows = CommodityFollow::where('user_id', $request->user()->id)
            ->with('commodity')
            ->get();

        return view('notifications.follows', compact('follows'));
    }
}
