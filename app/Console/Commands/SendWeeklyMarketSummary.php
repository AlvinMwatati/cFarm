<?php

namespace App\Console\Commands;

use App\Models\CommodityFollow;
use App\Models\MarketPrice;
use App\Notifications\WeeklyMarketSummary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendWeeklyMarketSummary extends Command
{
    protected $signature   = 'notifications:weekly-summary';
    protected $description = 'Send weekly market price summary to all users who follow commodities';

    public function handle(): int
    {
        $this->info('Sending weekly market summaries...');

        // Get all users who have follows with weekly summary enabled
        $userFollows = CommodityFollow::where('notify_weekly_summary', true)
            ->with(['user', 'commodity'])
            ->get()
            ->groupBy('user_id');

        $sent = 0;

        foreach ($userFollows as $userId => $follows) {
            $user      = $follows->first()->user;
            $summaries = collect();

            foreach ($follows as $follow) {
                $commodity = $follow->commodity;

                // Get this week's average vs last week's average
                $thisWeek = MarketPrice::forCommodity($commodity->name)
                    ->where('price_date', '>=', now()->startOfWeek())
                    ->avg('retail_price');

                $lastWeek = MarketPrice::forCommodity($commodity->name)
                    ->whereBetween('price_date', [
                        now()->subWeek()->startOfWeek(),
                        now()->subWeek()->endOfWeek(),
                    ])
                    ->avg('retail_price');

                if (!$thisWeek) continue;

                $change = $lastWeek
                    ? round((($thisWeek - $lastWeek) / $lastWeek) * 100, 1)
                    : 0;

                $summaries->push([
                    'commodity'     => $commodity->name,
                    'current_price' => number_format($thisWeek, 2),
                    'change'        => $change,
                    'unit'          => $commodity->unit->value,
                ]);
            }

            if ($summaries->isNotEmpty()) {
                $user->notify(new WeeklyMarketSummary($summaries));
                $sent++;
            }
        }

        $this->info("✅ Sent weekly summary to {$sent} users.");
        return self::SUCCESS;
    }
}
