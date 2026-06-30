<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CropController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Crop::all()]);
    }

    public function show(int $id): JsonResponse
    {
        $crop = Crop::with(['profile', 'soilSuitabilities', 'yieldBenchmarks'])->findOrFail($id);

        return response()->json(['data' => $crop]);
    }

    public function tips(Request $request, int $id): JsonResponse
    {
        $crop = Crop::findOrFail($id);

        $query = $crop->tips();

        if ($soilType = $request->query('soil_type')) {
            $query->where(fn ($q) => $q->where('soil_type', $soilType)->orWhereNull('soil_type'));
        }

        return response()->json(['data' => $query->get()]);
    }
}
