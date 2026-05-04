<?php

namespace App\Jobs;

use App\Models\ScraperLog;
use App\Services\Scraping\KamisScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ScrapeKamisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600; // 10 minutes max
    public int $tries   = 1;   // Don't retry on failure

    public function handle(KamisScraperService $scraper): void
    {
        // Mark as running in cache so the UI can poll it
        Cache::put('kamis_scraper_status', [
            'running'    => true,
            'started_at' => now()->toISOString(),
            'message'    => 'Scrape started...',
        ], now()->addSeconds(700));

        $startTime = now();

        try {
            $stats = $scraper->scrape();

            ScraperLog::create([
                'source'           => 'kamis',
                'success'          => $stats['downloaded'],
                'products_found'   => $stats['products_found'] ?? 0,
                'products_scraped' => $stats['products_scraped'] ?? 0,
                'rows_saved'       => $stats['rows_saved'],
                'rows_processed'   => $stats['rows_processed'],
                'errors'           => $stats['errors'],
                'duration_seconds' => now()->diffInSeconds($startTime),
            ]);

            Cache::put('kamis_scraper_status', [
                'running'    => false,
                'started_at' => null,
                'message'    => $stats['downloaded']
                    ? "✅ Done — {$stats['products_scraped']} products, {$stats['rows_saved']} rows saved."
                    : '❌ Scrape failed.',
                'success'    => $stats['downloaded'],
                'finished_at'=> now()->toISOString(),
            ], now()->addSeconds(3600));

        } catch (\Exception $e) {
            Cache::put('kamis_scraper_status', [
                'running'    => false,
                'message'    => '❌ Error: ' . $e->getMessage(),
                'success'    => false,
                'finished_at'=> now()->toISOString(),
            ], now()->addSeconds(3600));

            throw $e; // Let Laravel mark job as failed
        }
    }
}
