<?php

namespace App\Services\Scraping;

use App\Models\MarketPrice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Cache;

class KamisScraperService
{
    private string $baseUrl = 'https://kamis.kilimo.go.ke/site/market';

    // ─── Public Entry Point ───────────────────────────────────────────────────

    public function scrape(): array
    {
        $stats = [
            'downloaded'          => false,
            'products_attempted'  => 0,
            'products_with_data'  => 0,
            'products_scraped'    => 0,
            'rows_processed'      => 0,
            'rows_saved'          => 0,
            'errors'              => [],
        ];

        // Step 1: Discover product IDs
        try {
            $productIds = $this->discoverProductIds();
        } catch (\Exception $e) {
            $stats['errors'][] = 'Discovery failed: ' . $e->getMessage();
            return $stats;
        }

        if (empty($productIds)) {
            $stats['errors'][] = 'No product IDs found on KAMIS site.';
            return $stats;
        }

        $stats['products_found'] = count($productIds);
        $stats['downloaded']     = true;

        Log::info('KAMIS: Discovered ' . count($productIds) . ' products to scrape');

        // Step 2: Loop through each product and download its data
        foreach ($productIds as $id => $name) {
            $stats['products_attempted']++;

            try {
                $url = "{$this->baseUrl}?product={$id}&per-page=3000&export=excel";

                $response = Http::timeout(60)
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; cFarm/1.0)'])
                    ->get($url);

                if (!$response->successful() || strlen($response->body()) < 500) {
                    continue;
                }

                $result = $this->processExcel($response->body());

                $stats['rows_processed'] += $result['rows_processed'];
                $stats['rows_saved']     += $result['rows_saved'];
                $stats['errors']          = array_merge($stats['errors'], $result['errors']);

                if ($result['rows_processed'] > 0) {
                    $stats['products_scraped']++;
                }

                usleep(300000);
            } catch (\Exception $e) {
                $stats['errors'][] = "Product {$id} ({$name}): " . $e->getMessage();
                Log::warning("KAMIS scrape failed for product {$id}", ['error' => $e->getMessage()]);
            }
        }

        if ($stats['rows_saved'] > 0) {
            Cache::forget('has_kamis_pricing_data');
            Log::info('KAMIS: Cache cleared after successful data import.');
        }

        return $stats;
    }

    // ─── Product ID Discovery ─────────────────────────────────────────────────

    /**
     * Fetch the KAMIS market page and extract all product IDs
     * from the <select> dropdown HTML.
     *
     * Returns: ['273' => 'Dry Maize', '274' => 'Red Sorghum', ...]
     */
    private function discoverProductIds(): array
    {
        $response = Http::timeout(30)
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; cFarm/1.0)'])
            ->get($this->baseUrl);

        if (!$response->successful()) {
            throw new \Exception("KAMIS returned HTTP {$response->status()} on discovery");
        }

        $html     = $response->body();
        $products = [];

        preg_match_all(
            '/<option\s+value=["\'](\d+)["\']\s*>([^<]+)<\/option>/i',
            $html,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $id   = (int) $match[1];
            $name = trim(html_entity_decode($match[2]));

            if ($id > 0 && !empty($name)) {
                $products[$id] = $name;
            }
        }

        if (empty($products)) {
            throw new \Exception('No product IDs found in KAMIS HTML — site structure may have changed.');
        }

        return $products;
    }

    // /**
    //  * Hardcoded fallback list of products relevant to cFarm.
    //  * Used when HTML parsing fails.
    //  */
    // private function fallbackProductIds(): array
    // {
    //     return [
    //         273 => 'Dry Maize',
    //         274 => 'Red Sorghum',
    //         275 => 'Wheat',
    //         276 => 'Rice',
    //         277 => 'Green Grams',
    //         278 => 'Ground Nuts',
    //         279 => 'Beans Red Haricot (Wairimu)',
    //         280 => 'Beans (Yellow-Green)',
    //         290 => 'Red Irish potato',
    //         291 => 'Cabbages',
    //         292 => 'Sweet potatoes',
    //         293 => 'Carrots',
    //         294 => 'Tomatoes',
    //         295 => 'Beans Rosecoco',
    //         296 => 'Kales/Sukuma Wiki',
    //         297 => 'Dry Onions',
    //         298 => 'Avocado',
    //         299 => 'Mangoes',
    //         300 => 'Cow Milk(At collection point)',
    //     ];
    // }

    // ─── Excel Processing ─────────────────────────────────────────────────────

    private function processExcel(string $content): array
    {
        $result  = ['rows_processed' => 0, 'rows_saved' => 0, 'errors' => []];
        $tmpFile = tempnam(sys_get_temp_dir(), 'kamis_') . '.xls';

        try {
            file_put_contents($tmpFile, $content);

            $spreadsheet = IOFactory::load($tmpFile);
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, false, false);

            array_shift($rows); // Remove header row

            foreach ($rows as $row) {
                try {
                    $parsed = $this->parseRow($row);

                    if ($parsed === null) {
                        continue;
                    }

                    // Use whereDate() for the date column so the lookup works
                    // correctly regardless of whether the column is stored as
                    // a date or datetime type in the database.
                    $existing = MarketPrice::where('commodity_name', $parsed['commodity_name'])
                        ->where('classification', $parsed['classification'])
                        ->where('market', $parsed['market'])
                        ->whereDate('price_date', $parsed['price_date'])
                        ->first();

                    if ($existing === null) {
                        MarketPrice::create($parsed);
                        $result['rows_saved']++;
                    } else {
                        $existing->fill($parsed)->save();
                    }

                    $result['rows_processed']++;
                } catch (\Exception $e) {
                    $result['errors'][] = $e->getMessage();
                    $result['rows_processed']++;
                }
            }
        } catch (\Exception $e) {
            $result['errors'][] = 'Excel parse error: ' . $e->getMessage();
            Log::error('KAMIS: Excel processing failed', ['error' => $e->getMessage()]);
        } finally {
            if (file_exists($tmpFile)) {
                unlink($tmpFile);
            }
        }

        return $result;
    }

    // ─── Row Parsing ──────────────────────────────────────────────────────────

    private function parseRow(array $row): ?array
    {
        // Columns: Commodity, Classification, Grade, Sex, Market, Wholesale, Retail, Supply Volume, County, Date
        [$commodity, $classification, $grade, $sex, $market, $wholesale, $retail, $supply, $county, $date]
            = array_pad($row, 10, null);

        // Skip empty or header rows
        if (empty($commodity) || empty($market) || empty($county)) {
            return null;
        }

        // Skip rows that look like repeated headers
        if (strtolower(trim($commodity)) === 'commodity') {
            return null;
        }

        return [
            'commodity_name'  => trim($commodity),
            'classification'  => trim($classification ?? ''),
            'market'          => trim($market),
            'wholesale_price' => number_format((float) str_replace(',', '', $wholesale), 2, '.', ''),
            'retail_price'    => number_format((float) str_replace(',', '', $retail), 2, '.', ''),
            'unit'            => $this->parseUnit($wholesale ?? $retail ?? ''),
            'supply_volume'   => is_numeric($supply) ? (float) $supply : null,
            'county'          => trim($county),
            'price_date'      => $this->parseDate($date),
            'source'          => 'kamis',
        ];
    }

    private function parsePrice(?string $value): ?float
    {
        if (empty($value) || $value === '-') {
            return null;
        }

        preg_match('/[\d]+\.?\d*/', str_replace(',', '', $value), $matches);

        return isset($matches[0]) ? (float) $matches[0] : null;
    }

    private function parseUnit(?string $value): string
    {
        if (empty($value)) {
            return 'Kg';
        }

        if (preg_match('/\/(\w+)/', $value, $matches)) {
            return ucfirst(strtolower($matches[1]));
        }

        return 'Kg';
    }

    private function parseDate(mixed $value): string
    {
        if (empty($value)) {
            return now()->toDateString();
        }

        // Excel numeric date (e.g. 46474)
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return now()->toDateString();
            }
        }

        // String date like "2026-03-25"
        try {
            return \Carbon\Carbon::parse($value)->toDateString();
        } catch (\Exception $e) {
            return now()->toDateString();
        }
    }
}
