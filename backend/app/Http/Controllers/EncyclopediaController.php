<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropProfile;
use App\Models\CropYieldBenchmark;
use App\Services\FaostatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EncyclopediaController extends Controller
{
    public function __construct(private FaostatService $faostat) {}

    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|min:2|max:100']);

        $importedCodes = CropProfile::whereNotNull('faostat_item_code')
            ->pluck('faostat_item_code')
            ->flip();

        $results = $this->faostat->searchItems($request->string('q'));

        $data = array_map(fn ($item) => [
            'faostat_code'     => $item['code'],
            'name'             => $item['name'],
            'already_imported' => $importedCodes->has($item['code']),
        ], $results);

        return response()->json(['data' => $data]);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'faostat_item_code' => 'required|string|max:20',
            'name'              => 'required|string|max:255',
        ]);

        $code = $request->string('faostat_item_code')->toString();

        $existing = CropProfile::where('faostat_item_code', $code)->with('crop')->first();
        if ($existing) {
            return response()->json([
                'message' => 'This crop has already been imported.',
                'data'    => $this->formatCrop($existing->crop->load(['profile', 'soilSuitabilities', 'yieldBenchmarks'])),
            ], 409);
        }

        $crop = Crop::firstOrCreate(
            ['name' => $request->string('name')->toString()],
            ['category' => 'other', 'icon' => 'fa-solid fa-seedling']
        );

        $crop->profile()->updateOrCreate(
            ['crop_id' => $crop->id],
            ['faostat_item_code' => $code, 'faostat_imported_at' => now()]
        );

        $benchmarks = $this->faostat->fetchYieldBenchmarks($code);
        foreach ($benchmarks as $b) {
            CropYieldBenchmark::updateOrCreate(
                ['crop_id' => $crop->id, 'country_code' => 'WLD', 'year' => $b['year']],
                ['yield_kg_ha' => $b['yield_kg_ha']]
            );
        }

        $crop->load(['profile', 'soilSuitabilities', 'yieldBenchmarks']);

        return response()->json(['data' => $this->formatCrop($crop)], 201);
    }

    private function formatCrop(Crop $crop): array
    {
        return [
            'id'              => $crop->id,
            'name'            => $crop->name,
            'scientific_name' => $crop->scientific_name,
            'category'        => $crop->category,
            'avg_growth_days' => $crop->avg_growth_days,
            'icon'            => $crop->icon,
            'profile'         => $crop->profile,
            'soil_suitabilities' => $crop->soilSuitabilities,
            'yield_benchmarks'   => $crop->yieldBenchmarks,
        ];
    }
}
