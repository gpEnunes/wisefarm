<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\JsonResponse;

/**
 * Provides read-only access to the crop reference catalogue.
 */
class CropController extends Controller
{
    /**
     * Return all available crop types.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(['data' => Crop::all()]);
    }
}
