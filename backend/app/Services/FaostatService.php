<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaostatService
{
    private const BASE_URL = 'https://fenix.fao.org/faostat/api/v1/en';
    private const ITEMS_CACHE_KEY = 'faostat:items:QCL';
    private const ITEMS_CACHE_TTL = 86400; // 24h

    /**
     * Search FAOSTAT crop items by name.
     * Item list is cached in Redis for 24 hours.
     *
     * @return array<int, array{code: string, name: string}>
     */
    public function searchItems(string $query): array
    {
        $items = Cache::remember(self::ITEMS_CACHE_KEY, self::ITEMS_CACHE_TTL, function () {
            return $this->fetchItemList();
        });

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
     *
     * @return array<int, array{year: int, yield_kg_ha: float}>
     */
    public function fetchYieldBenchmarks(string $itemCode): array
    {
        try {
            $response = Http::timeout(15)->get(self::BASE_URL . '/data/QCL', [
                'item'    => $itemCode,
                'element' => '5412',
                'area'    => '5000',
                'year'    => implode(',', range(2013, 2023)),
            ]);

            if (! $response->successful()) {
                Log::warning('FAOSTAT yield fetch failed', ['item' => $itemCode, 'status' => $response->status()]);
                return [];
            }

            $data = $response->json('data', []);

            return collect($data)
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
     * Fetch and return the full item list from FAOSTAT domain QCL.
     *
     * @return array<int, array{code: string, name: string}>
     */
    private function fetchItemList(): array
    {
        try {
            $response = Http::timeout(30)->get(self::BASE_URL . '/definitions/domain/QCL/items');

            if (! $response->successful()) {
                Log::warning('FAOSTAT item list fetch failed', ['status' => $response->status()]);
                return [];
            }

            return collect($response->json('data', []))
                ->map(fn ($item) => [
                    'code' => (string) $item['code'],
                    'name' => $item['label'],
                ])
                ->values()
                ->all();
        } catch (\Throwable $e) {
            Log::warning('FAOSTAT item list fetch exception', ['error' => $e->getMessage()]);
            return [];
        }
    }
}
