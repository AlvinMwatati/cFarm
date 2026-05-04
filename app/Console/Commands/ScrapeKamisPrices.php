<?php

namespace App\Console\Commands;

use App\Services\Scraping\KamisScraperService;
use Illuminate\Console\Command;
use App\Models\ScraperLog;

class ScrapeKamisPrices extends Command
{
    protected $signature   = 'kamis:scrape {--dry-run : Run without saving to database}';
    protected $description = 'Scrape latest market prices from KAMIS';

    public function handle(KamisScraperService $scraper): int
{
    $this->info('Starting KAMIS price scrape...');
    $startTime = now();
    
    $this->info('Discovering product list from KAMIS...');

    $duration = now()->diffInSeconds($startTime);

    $stats = $scraper->scrape();

     // Log the run
    ScraperLog::create([
        'source'            => 'kamis',
        'success'           => $stats['downloaded'],
        'products_found'    => $stats['products_found'] ?? 0,
        'products_scraped'  => $stats['products_scraped'] ?? 0,
        'rows_saved'        => $stats['rows_saved'],
        'rows_processed'    => $stats['rows_processed'],
        'errors'            => $stats['errors'],
        'duration_seconds'  => $duration,
    ]);

    if ($stats['downloaded']) {
        $this->info('✅ Scrape complete');
        $this->info("   Products attempted:  {$stats['products_attempted']}");
        $this->info("   Products with data:  {$stats['products_with_data']}");
        $this->info("   Rows processed:      {$stats['rows_processed']}");
        $this->info("   Rows saved:          {$stats['rows_saved']}");
    } else {
        $this->error('❌ Scrape failed — no data downloaded');
    }

    if (!empty($stats['errors'])) {
        $this->warn('Errors (' . count($stats['errors']) . '):');
        foreach (array_slice($stats['errors'], 0, 10) as $error) {
            $this->warn("  - {$error}");
        }
    }

    return $stats['downloaded'] ? self::SUCCESS : self::FAILURE;
}
}
