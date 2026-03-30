<?php

namespace Tests\Feature;

use App\Models\MarketPrice;
use App\Services\Scraping\KamisScraperService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class KamisScraperTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function scraper_saves_market_prices_from_excel(): void
    {
        Http::fake([
            // Discovery page — returns HTML with one product option
            'kamis.kilimo.go.ke/site/market' => Http::response(
                '<html><select name="product"><option value="273">Dry Maize</option></select></html>',
                200
            ),
            // Excel download for that product
            'kamis.kilimo.go.ke/site/market*' => Http::response(
                $this->fakeExcelResponse(),
                200,
                ['Content-Type' => 'application/vnd.ms-excel']
            ),
        ]);

        $scraper = new KamisScraperService();
        $stats   = $scraper->scrape();

        $this->assertTrue($stats['downloaded']);
        $this->assertGreaterThan(0, $stats['rows_saved']);
        $this->assertDatabaseHas('market_prices', ['source' => 'kamis']);
    }

    #[Test]
    public function scraper_handles_failed_download_gracefully(): void
    {
        // All requests fail including discovery
        Http::fake([
            'kamis.kilimo.go.ke/*' => Http::response('', 503),
        ]);

        $scraper = new KamisScraperService();
        $stats   = $scraper->scrape();

        $this->assertFalse($stats['downloaded']);
        $this->assertNotEmpty($stats['errors']);
        $this->assertDatabaseCount('market_prices', 0);
    }

    #[Test]
    public function scraper_avoids_duplicate_entries(): void
    {
        $discoveryHtml  = '<html><select name="product"><option value="273">Dry Maize</option></select></html>';
        $excelResponse  = $this->fakeExcelResponse();

        Http::fake([
            'kamis.kilimo.go.ke/site/market' => Http::response($discoveryHtml, 200),
            'kamis.kilimo.go.ke/site/market*' => Http::response(
                $excelResponse,
                200,
                ['Content-Type' => 'application/vnd.ms-excel']
            ),
        ]);

        $scraper = new KamisScraperService();

        $scraper->scrape();
        $firstCount = MarketPrice::count();

        // Re-fake for second run
        Http::fake([
            'kamis.kilimo.go.ke/site/market' => Http::response($discoveryHtml, 200),
            'kamis.kilimo.go.ke/site/market*' => Http::response(
                $excelResponse,
                200,
                ['Content-Type' => 'application/vnd.ms-excel']
            ),
        ]);

        $scraper->scrape();
        $secondCount = MarketPrice::count();

        $this->assertEquals($firstCount, $secondCount);
    }

    private function fakeExcelResponse(): string
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->fromArray([
            ['Commodity', 'Classification', 'Grade', 'Sex', 'Market', 'Wholesale', 'Retail', 'Supply Volume', 'County', 'Date'],
            ['Dry Maize', 'White Maize', '-', '-', 'Kawangware', '55.00/Kg', '65.00/Kg', '1000', 'Nairobi', '2026-03-26'],
        ]);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }
}
