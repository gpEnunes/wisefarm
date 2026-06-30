<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaostatService
{
    private const YIELD_API_URL = 'https://fenix.fao.org/faostat/api/v1/en';
    private const ITEMS_FILE    = 'faostat_crops.json';

    /**
     * Search the bundled FAOSTAT crop list by name (case-insensitive substring).
     * The list lives at database/data/faostat_crops.json — no network call needed.
     *
     * @return array<int, array{code: string, name: string}>
     */
    public function searchItems(string $query): array
    {
        $items = $this->loadItemList();
        $query = mb_strtolower(trim($query));

        return collect($items)
            ->filter(fn ($item) => str_contains(mb_strtolower($item['name']), $query))
            ->values()
            ->take(20)
            ->all();
    }

    /**
     * Fetch global average yield benchmarks for a FAOSTAT item code.
     * Returns data for 2013–2023, world aggregate (area=5000).
     * Element 5412 = Yield in Hg/Ha → converted to kg/ha (÷ 100).
     * Returns [] silently when the FAOSTAT API is unreachable.
     *
     * @return array<int, array{year: int, yield_kg_ha: float}>
     */
    public function fetchYieldBenchmarks(string $itemCode): array
    {
        try {
            $response = Http::timeout(15)->get(self::YIELD_API_URL . '/data/QCL', [
                'item'    => $itemCode,
                'element' => '5412',
                'area'    => '5000',
                'year'    => implode(',', range(2013, 2023)),
            ]);

            if (! $response->successful()) {
                Log::warning('FAOSTAT yield fetch failed', ['item' => $itemCode, 'status' => $response->status()]);
                return [];
            }

            return collect($response->json('data', []))
                ->map(fn ($row) => [
                    'year'        => (int) $row['Year'],
                    'yield_kg_ha' => round((float) $row['Value'] / 100, 2),
                ])
                ->sortBy('year')
                ->values()
                ->all();
        } catch (\Throwable $e) {
            Log::warning('FAOSTAT yield fetch exception', ['item' => $itemCode, 'error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Load the bundled FAOSTAT item list from database/data/faostat_crops.json.
     *
     * @return array<int, array{code: string, name: string}>
     */
    private function loadItemList(): array
    {
        $path = database_path('data/' . self::ITEMS_FILE);

        if (! file_exists($path)) {
            Log::error('FAOSTAT bundled item list missing', ['path' => $path]);
            return [];
        }

        $decoded = json_decode(file_get_contents($path), true);

        return is_array($decoded) ? $decoded : [];
    }
}
