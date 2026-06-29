<?php

namespace App\Http\Controllers;

use App\Http\Requests\FarmRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Manages CRUD operations for the Farm resource.
 * All actions are automatically scoped to the authenticated user.
 */
class FarmController extends Controller
{
    /**
     * List all farms belonging to the authenticated user (paginated).
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $farms = $request->user()
            ->farms()
            ->withCount('fields')
            ->paginate(15);

        return response()->json($farms);
    }

    /**
     * Create a new farm for the authenticated user.
     *
     * @param  FarmRequest $request
     * @return JsonResponse         201 with created farm data
     */
    public function store(FarmRequest $request): JsonResponse
    {
        $farm = $request->user()->farms()->create($request->validated());

        return response()->json(['data' => $farm], 201);
    }

    /**
     * Return a single farm (with its fields) owned by the authenticated user.
     *
     * @param  Request $request
     * @param  int     $farm    Farm primary key
     * @return JsonResponse
     */
    public function show(Request $request, int $farm): JsonResponse
    {
        $farm = $request->user()->farms()->with('fields')->findOrFail($farm);

        return response()->json(['data' => $farm]);
    }

    /**
     * Update a farm owned by the authenticated user.
     *
     * @param  FarmRequest $request
     * @param  int         $farm    Farm primary key
     * @return JsonResponse
     */
    public function update(FarmRequest $request, int $farm): JsonResponse
    {
        $farm = $request->user()->farms()->findOrFail($farm);
        $farm->update($request->validated());

        return response()->json(['data' => $farm->fresh()]);
    }

    /**
     * Delete a farm owned by the authenticated user.
     *
     * @param  Request $request
     * @param  int     $farm    Farm primary key
     * @return JsonResponse     204 No Content
     */
    public function destroy(Request $request, int $farm): JsonResponse
    {
        $request->user()->farms()->findOrFail($farm)->delete();

        return response()->json(null, 204);
    }
}
